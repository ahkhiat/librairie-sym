<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\AuteurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuteurController extends AbstractController
{
    #[Route('/auteurs', name: 'app_auteurs')]
    public function index(AuteurRepository $auteurRepository): Response
    {
        return $this->render('auteur/index.html.twig', [
            'auteurs' => $auteurRepository->all_auteurs(),
        ]);
    }

    #[Route('/auteurs/{auteur_id}', name: 'app_auteur_fiche', methods: ['GET'])]
    public function ficheLivre(AuteurRepository $auteurRepository, $auteur_id): Response
    {
        return $this->render('auteur/auteur_fiche.html.twig', [
            'livres' => $auteurRepository->auteur_show($auteur_id),
        ]);
    }
}