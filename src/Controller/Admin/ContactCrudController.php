<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ContactCrudController extends AbstractCrudController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Message')
            ->setEntityLabelInPlural('Messages')
            ->setSearchFields(['nom', 'email', 'sujet', 'message'])
            ->setDefaultSort(['date_message' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewDetailAction = Action::new('viewDetail', 'View Details')
            ->linkToCrudAction('viewDetail')
            ->addCssClass('btn btn-info');

        return $actions
            ->add(Crud::PAGE_INDEX, $viewDetailAction)
            ->add(Crud::PAGE_DETAIL, $viewDetailAction);
    }

    // #[Route('/admin/contact/{id}/viewDetail', name: 'contact_view_detail')]
    public function viewDetail(Request $request): Response
    {
        $id = $request->query->get('entityId');
        $contact = $this->entityManager->getRepository(Contact::class)->find($id);

        if ($contact && !$contact->isRead()) {
            $contact->setRead(true);
            $this->entityManager->flush();
        }

        return $this->redirect($this->generateUrl('admin', [
            'action' => 'detail',
            'entity' => 'Contact',
            'id' => $id,
        ]));
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom', 'Nom et Prénom'),
            EmailField::new('email', 'Email'),
            TextField::new('sujet', 'Objet'),
            TextareaField::new('message', 'Message'),
            DateTimeField::new('dateMessage', 'Date d\'envoi')->hideOnForm(),
            BooleanField::new('isRead', 'Lu')->onlyOnIndex(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('isRead');
    }
}
