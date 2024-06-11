<?php

namespace App\Controller;

use App\Repository\PanierArticleRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HeaderController extends AbstractController
{
    private $panierArticleRepository;
    private $security;

    public function __construct(PanierArticleRepository $panierArticleRepository, Security $security)
    {
        $this->panierArticleRepository = $panierArticleRepository;
        $this->security = $security;
    }

    #[Route('/header/cart', name: 'header_cart')]

    public function headerCart(): Response
    {
        $user = $this->security->getUser();
        $articleCount = 0;

        if ($user) {
            $articleCount = $this->panierArticleRepository->countArticlesInCart($user->getId());
        }

        return $this->render('headerCart.html.twig', [
            'articleCount' => $articleCount,
        ]);
    }
}
