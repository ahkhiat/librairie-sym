<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Commande;
use App\Service\CommandeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    private $commandeService;

    public function __construct(CommandeService $commandeService)
    {
        $this->commandeService = $commandeService;
    }

    #[Route('/commander', name: 'app_commande')]
    public function commander(): Response
    {
        
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non connecté');
        }

        $panier = $user->getPanierActif();

        $commande = $this->commandeService->transformerPanierEnCommande($panier);

        // Rediriger vers la page de confirmation de commande ou afficher un message de succès
        return $this->redirectToRoute('app_confirmation_commande', ['id' => $commande->getId()]);
    }

    #[Route('/commander/confirmation/{id}', name: 'app_confirmation_commande')]
    public function confirmation(Commande $commande): Response
    {
        return $this->render('commande/confirmation.html.twig', [
            'commande' => $commande,
        ]);
    }
}
