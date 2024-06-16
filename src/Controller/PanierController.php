<?php

namespace App\Controller;

use App\Entity\PanierArticle;
use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PanierArticleRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PanierController extends AbstractController
{
    private $panierArticleRepository;
    private $entityManager;

    public function __construct(PanierArticleRepository $panierArticleRepository, EntityManagerInterface $entityManager)
    {
        $this->panierArticleRepository = $panierArticleRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/panier', name: 'app_panier')]
    public function index(PanierArticleRepository $panierArticleRepository, TokenStorageInterface $tokenStorage): Response
    {
        $token = $tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('User not authenticated or invalid user type');
        }

        $user_id = $user->getId();

        return $this->render('panier/index.html.twig', [
            'livres' => $panierArticleRepository->panier_contenu($user_id),
        ]);
    }

    #[Route('/ajoutpanier', name: 'app_panier_ajout',  methods: ['POST'])]
    public function ajoutPanier(Request $request, Connection $connection, TokenStorageInterface $tokenStorage): Response
    {
        $data = json_decode($request->getContent(), true);
        $articleId = $data['edition_id'];
        $userId = $data['user_id'];
        $quantite = $data['quantite'];

        if (!$userId) {
            return new Response('User not authenticated', 401);
        }

        try {
            $connection->beginTransaction();

            // Vérifier si le panier existe pour l'utilisateur
            $stmt = $connection->executeQuery("SELECT id FROM panier WHERE user_id = ?", [$userId]);
            $panier = $stmt->fetchAssociative();

            if (!$panier) {
                $dateObjet = new \DateTime();
                $datePanier = $dateObjet->format('Y-m-d H:i:s');
                // Créer un nouveau panier
                $connection->executeStatement("INSERT INTO panier (user_id, created_at) VALUES (?, ?)", [$userId, $datePanier]);
                $panierId = $connection->lastInsertId();
            } else {
                $panierId = $panier['id'];
            }

            // Vérifier si l'article existe déjà dans le panier
            $stmt = $connection->executeQuery("SELECT quantite FROM panier_article WHERE panier_id = ? AND edition_id = ?", [$panierId, $articleId]);
            $panierArticle = $stmt->fetchAssociative();

            if ($panierArticle) {
                // Mettre à jour la quantité de l'article existant
                $newQuantity = $panierArticle['quantite'] + 1;
                $connection->executeStatement("UPDATE panier_article SET quantite = ? WHERE panier_id = ? AND edition_id = ?", [$newQuantity, $panierId, $articleId]);
            } else {
                // Ajouter un nouvel article au panier
                $connection->executeStatement("INSERT INTO panier_article (panier_id, edition_id, quantite) VALUES (?, ?, ?)", [$panierId, $articleId, $quantite]);
            }

            $connection->commit();

            return new Response('Article ajouté au panier avec succès');
        } catch (\Exception $e) {
            $connection->rollBack();
            return new Response('Erreur lors de l\'ajout de l\'article au panier : ' . $e->getMessage(), 500);
        }
    }

   
    #[Route('/remove-item', name: 'remove_item',  methods: ['POST'])]
    public function removeItem(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $article = $this->panierArticleRepository->find($data['edition_id']);

        if ($article) {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
            return $this->json(['success' => true]);
        }

        return $this->json(['success' => false], Response::HTTP_BAD_REQUEST);
    }

    #[Route('/panierupdate', name: 'update_cart', methods: ['POST'])]
    public function updateCart(Request $request, EntityManagerInterface $em, LoggerInterface $logger): JsonResponse
    {
        $logger->info('updateCart called');
        $data = json_decode($request->getContent(), true);

        // Vérifiez la validité des données
        if (!isset($data['article_id']) || !isset($data['quantity'])) {
            $logger->error('Invalid data received', ['data' => $data]);
            return new JsonResponse(['success' => false, 'message' => 'Invalid data'], 400);
        }

        $articleId = $data['article_id'];
        $quantity = $data['quantity'];

        $logger->info('Data received', ['article_id' => $articleId, 'quantity' => $quantity]);

        // Recherchez l'article du panier correspondant
        $cartItem = $em->getRepository(PanierArticle::class)->findOneBy([
            'edition' => $articleId,
            'user' => $this->getUser()
        ]);

        if (!$cartItem) {
            $logger->error('Item not found', ['article_id' => $articleId]);
            return new JsonResponse(['success' => false, 'message' => 'Item not found'], 404);
        }

        // Mettez à jour la quantité
        $cartItem->setQuantite($quantity);
        $em->persist($cartItem);
        $em->flush();

        $logger->info('Cart updated successfully', ['article_id' => $articleId, 'quantity' => $quantity]);

        return new JsonResponse(['success' => true]);
    }

}
