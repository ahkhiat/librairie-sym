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
    #[Route('/livres', name: 'app_livres')]
    public function index(LivreRepository $livreRepository): Response
    {
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->all_livres(),
            'titre_page' => 'Tous les livres',
        ]);
    }

    #[Route('/livres/{edition_id}', name: 'app_livre_fiche', methods: ['GET'])]
    public function ficheLivre(EditionRepository $editionRepository, $edition_id): Response
    {
        return $this->render('livre/livre_fiche.html.twig', [
            'livre' => $editionRepository->livre_show($edition_id),
        ]);
    }

    #[Route('/livres/categories/romans', name: 'app_livres_romans')]
    public function cat_romans(LivreRepository $livreRepository): Response
    {
        $theme_livre = 'roman';

        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->all_livres_by_theme($theme_livre),
            'titre_page' => 'Romans',
            'theme' => $theme_livre,
        ]);
    }

    #[Route('/livres/categories/policiers', name: 'app_livres_policiers')]
    public function cat_policiers(LivreRepository $livreRepository): Response
    {
        $theme_livre = 'policier';
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->all_livres_by_theme($theme_livre),
            'titre_page' => 'Policiers',
            'theme' => $theme_livre,
        ]);
    }
    #[Route('/livres/categories/scifi', name: 'app_livres_scifi')]
    public function cat_scifi(LivreRepository $livreRepository): Response
    {
        $theme_livre = 'science-fiction';
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->all_livres_by_theme($theme_livre),
            'titre_page' => 'Science-fiction',
            'theme' => $theme_livre,
        ]);
    }

    #[Route('/livres/categories/classiques', name: 'app_livres_classiques')]
    public function cet_classiques(LivreRepository $livreRepository): Response
    {
        $theme_livre = 'classique';
        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->all_livres_by_theme($theme_livre),
            'titre_page' => 'Classiques',
            'theme' => $theme_livre,

        ]);
    }

    #[Route('/livres/categories/pratiques', name: 'app_livres_pratiques')]
    public function cat_pratiques(LivreRepository $livreRepository): Response
    {
        $theme_livre = 'pratique';

        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->all_livres_by_theme($theme_livre),
            'titre_page' => 'Pratiques',
            'theme' => $theme_livre,

        ]);
    }

    #[Route('/livres/categories/biographies', name: 'app_livres_biographies')]
    public function cat_biographies(LivreRepository $livreRepository): Response
    {
        $theme_livre = 'biographie';

        return $this->render('livre/index.html.twig', [
            'livres' => $livreRepository->all_livres_by_theme($theme_livre),
            'titre_page' => 'Biographies',
            'theme' => $theme_livre,

        ]);
    }


}
