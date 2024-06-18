<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\FactureLigne;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FactureController extends AbstractController
{
    #[Route('/facture', name: 'app_facture')]
    public function index(): Response
    {
        return $this->render('facture/index.html.twig', [
            'controller_name' => 'FactureController',
        ]);
    }

    #[Route('/facture/{id}', name: 'show_facture')]
    public function show(EntityManagerInterface $em, $id): Response
    {
        $facture = $em->getRepository(Facture::class)->find($id);

        if (!$facture) {
            throw $this->createNotFoundException('La facture n\'existe pas.');
        }

        // Récupérer les lignes de facture associées
        $factureLignes = [];
        if ($facture) {
            $factureLignes = $em->getRepository(FactureLigne::class)->findBy(['facture' => $facture]);
        }

        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
            'factureLignes' => $factureLignes,
        ]);
    }
}
