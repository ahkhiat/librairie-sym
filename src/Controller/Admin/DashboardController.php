<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Contact;
use App\Entity\Editeur;
use App\Entity\Edition;
use App\Entity\Commande;
use App\Entity\LivreAuteur;
use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }
    
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Librairie Sym');
    }

    public function configureMenuItems(): iterable
    {
        $unreadMessages = $this->contactRepository->countUnreadMessages();

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');


        yield MenuItem::linkToCrud('Auteur', 'fas fa-user', Auteur::class);
        yield MenuItem::linkToCrud('Editeur', 'fas fa-building', Editeur::class);
        yield MenuItem::linkToCrud('Livre', 'fas fa-book', Livre::class);
        yield MenuItem::linkToCrud('Editions', 'fas fa-user', Edition::class);
        yield MenuItem::linkToCrud('LivreAuteur', 'fas fa-user', LivreAuteur::class);


        yield MenuItem::section('Utilisateurs');

        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Commande', 'fas fa-user', Commande::class);

        yield MenuItem::section('Messages');
        yield MenuItem::linkToCrud('Contact', 'fas fa-message', Contact::class)
            ->setBadge($unreadMessages, 'badge badge-danger');



        yield MenuItem::linkToUrl('Home', 'fa fa-home', $this->generateUrl('app_home'));



        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
