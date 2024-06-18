<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\EditionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contact);
            $em->flush();

            $this->addFlash('success', 'Votre message a été envoyé avec succès!');

            return $this->redirectToRoute('app_home');
        }
        
        $randomEditions = $this->editionRepository->findRandomEditions(4);


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'randomEditions' => $randomEditions,
            'form' => $form->createView(),
        ]);
    }
}
