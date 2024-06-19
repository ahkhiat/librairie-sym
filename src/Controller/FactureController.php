<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\FactureLigne;
use DateTime;
use Konekt\PdfInvoice\InvoicePrinter;
use Symfony\Component\Asset\Packages;
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

    #[Route('/facture/{id}/pdf', name: 'facture_pdf')]
    public function generatePdf(int $id, EntityManagerInterface $em,  Packages $assets)
    {
        // Récupérer la facture par ID
        $facture = $em->getRepository(Facture::class)->find($id);

        $invoice = new InvoicePrinter();

        $logoWebPath = $assets->getUrl('/images/site/pickabook.png');
        $logoAbsolutePath = $this->getParameter('kernel.project_dir') . '/public/images/site/pickabook.png';

        $date = new DateTime();
        // Définir les paramètres de la facture
        $invoice->setLogo($logoAbsolutePath);   // Mettre à jour avec le chemin de votre logo
        $invoice->setColor("#ff8c08");           // Couleur
        $invoice->setType("Facture");            // Type de document
        $invoice->setReference($date->format('Y').$date->format('m').'00'.$id);  // Référence de la facture
        $invoice->setDate(date('d m Y', time()));  // Date de la facture
        $invoice->setTime(date('h:i:s A', time()));  // Heure de la facture
        $invoice->setFrom(array("PickaBook", "54 bd Laveran", "13013 Marseille", "France"));
        $invoice->setTo(array($facture->getUser()->getPrenom() .' '. $facture->getUser()->getNom(), $facture->getUser()->getAdresse(), 
                              $facture->getUser()->getCodePostal().' '.$facture->getUser()->getVille()));
 

        // Ajouter les lignes de la facture
        foreach ($facture->getFactureLignes() as $ligne) {

            $prixVente = number_format(($ligne->getPrixVente()/100), 2, ',', ' ').'€';
            $total = number_format(($ligne->getQuantite() * $ligne->getPrixVente()/100), 2, ',', ' ').'€';
            $totalFacture = number_format(($facture->getCommande()->getCoutTotal())/100, 2, ',', ' ').'€';

            $invoice->addItem($ligne->getEdition()->getLivre()->getTitreLivre(), 
                              '', // description vide
                              $ligne->getQuantite(), 
                              0,
                              $prixVente, 
                              0,
                              $total
                              );
        }

        // Définir les totaux
        $invoice->addTotal("Total", $totalFacture);

        // Générer le PDF
        $pdfContent = $invoice->render();

        return new Response(
            $pdfContent,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="facture.pdf"',
            ]
        );
        }


}



