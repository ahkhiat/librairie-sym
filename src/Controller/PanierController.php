<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\PanierArticleRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PanierController extends AbstractController
{

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

        if (!$userId) {
            return new Response('User not authenticated', 401);
        }

        try {
            $connection->beginTransaction();

            // Vérifier si le panier existe pour l'utilisateur
            $stmt = $connection->executeQuery("SELECT id FROM panier WHERE user_id = ?", [$userId]);
            $panier = $stmt->fetchAssociative();

            if (!$panier) {
                // Créer un nouveau panier
                $connection->executeStatement("INSERT INTO panier (user_id) VALUES (?)", [$userId]);
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
                $connection->executeStatement("INSERT INTO panier_article (panier_id, edition_id, quantite) VALUES (?, ?, 1)", [$panierId, $articleId]);
            }

            $connection->commit();

            return new Response('Article ajouté au panier avec succès');
        } catch (\Exception $e) {
            $connection->rollBack();
            return new Response('Erreur lors de l\'ajout de l\'article au panier : ' . $e->getMessage(), 500);
        }
    }


}
