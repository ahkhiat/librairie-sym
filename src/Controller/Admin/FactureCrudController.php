<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FactureCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Facture::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('commande')
                ->setLabel('Commande')
                ->setCrudController(CommandeCrudController::class),
            DateTimeField::new('commande.dateCommande')
                ->setLabel('Date de Commande')
                ,
            MoneyField::new('commande.coutTotal')
                ->setCurrency('EUR')
                ->setLabel('Coût Total')
                ,
            TextField::new('commande.statut')->setLabel('Statut'),
            ArrayField::new('commandeArticles')->onlyOnDetail()->setLabel('Lignes de Commande')
        ];
    }
    
}
