<?php

namespace App\Controller;

use App\Repository\EditionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $editionRepository;

    public function __construct(EditionRepository $editionRepository)
    {
        $this->editionRepository = $editionRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $randomEditions = $this->editionRepository->findRandomEditions(4);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'randomEditions' => $randomEditions,
        ]);
    }
}
