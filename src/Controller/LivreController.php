<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\EditionRepository;
use App\Repository\LivreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LivreController extends AbstractController
{
    #[Route('/livres', name: 'app_livre')]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->all_livres(),
        ]);
    }

    #[Route('/livres/{edition_id}', name: 'app_livre_fiche', methods: ['GET'])]
    public function ficheLivre(EditionRepository $editionRepository, $edition_id): Response
    {
        return $this->render('livre/livre_fiche.html.twig', [
            'livre' => $editionRepository->livre_show($edition_id),
        ]);
    }
}
