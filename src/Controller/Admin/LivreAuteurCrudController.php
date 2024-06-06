<?php

namespace App\Controller\Admin;

use App\Entity\LivreAuteur;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LivreAuteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return LivreAuteur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('livre')->setSortProperty('titre_livre'),
            AssociationField::new('auteur')->setSortProperty('nom'),
        ];
    }
}
