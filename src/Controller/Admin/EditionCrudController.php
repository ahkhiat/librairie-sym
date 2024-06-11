<?php

namespace App\Controller\Admin;

use App\Entity\Edition;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EditionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Edition::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Edition')
            ->setEntityLabelInPlural('Editions')
            ->setSearchFields(['livre.titre_livre', 'editeur.nom_editeur', 'livre.auteur.nom_auteur'])
            ->setDefaultSort(['annee_edition' => 'DESC'])
            ->showEntityActionsInlined()
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des Éditions');
    }
    
    public function configureFields(string $pageName): iterable
    {
        $years = range(date('Y'), 1900);

        return [
            AssociationField::new('livre')->setSortProperty('titre_livre'),
            AssociationField::new('editeur')->setSortProperty('nom_editeur'),
            ChoiceField::new('annee_edition')->setChoices(array_combine($years, $years))->setLabel('Année d\'édition'),
            IntegerField::new('nbr_pages'),
            MoneyField::new('prix_vente')->setCurrency('EUR'),            
            TextField::new('format'),
            ImageField::new('imageName')
                ->setBasePath('/images/editions')
                ->setUploadDir('public/images/editions')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            IntegerField::new('stock'),
            IntegerField::new('alerte'),



            // ImageField::new('imageFile')
            //     ->setFormType(VichImageType::class)
            //     ->setUploadDir('public/images/editions')
            //     ->onlyOnForms()
            //     ->setLabel('Image Upload'),

        ];
    }
    
}
