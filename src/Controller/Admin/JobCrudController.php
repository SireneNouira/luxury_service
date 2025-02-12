<?php

namespace App\Controller\Admin;

use App\Entity\JobOfferType;
use App\Entity\Category;
use App\Entity\Client;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JobCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobOfferType::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('salary'),
            TextField::new('name'),
            BooleanField::new('active'),
            IdField::new('id')->onlyOnIndex(),
            TextEditorField::new('description'),
            AssociationField::new('category')
                ->setFormTypeOption('choice_label', 'name')
                ->setFormTypeOption('by_reference', false),
            AssociationField::new('client') // 'client' doit correspondre au nom de la relation dans votre entité
                ->setLabel('Client') // Étiquette affichée
                ->setFormTypeOptions([
                    'class' => Client::class, // Entité Client
                    'choice_label' => 'name', // Nom ou champ à afficher pour chaque client (vous pouvez ajuster selon votre modèle)
                    'placeholder' => 'Sélectionner un client', // Texte à afficher dans la liste déroulante avant sélection
                ])
                ->onlyOnForms()
        ];
    }
}
