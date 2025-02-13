<?php
namespace App\Controller\Client;

use App\Entity\Client;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Symfony\Bundle\SecurityBundle\Security;

class ClientCrudController extends AbstractCrudController
{
    private Security $security;
    private EntityManagerInterface $entityManager;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function createIndexQueryBuilder($entityClass, $sortDirection, $sortField = null, $filters = null): QueryBuilder
    {
        // Récupérer l'utilisateur connecté
        $user = $this->security->getUser();

        // Créer le QueryBuilder de base
        $queryBuilder = parent::createIndexQueryBuilder($entityClass, $sortDirection, $sortField, $filters);

        // Filtrer les clients pour n'afficher que celui associé à l'utilisateur connecté
        if ($user) {
            $queryBuilder
                ->andWhere('entity.user = :user')
                ->setParameter('user', $user);
        }

        return $queryBuilder;
    }
    public function configureActions(Actions $actions): Actions
    {
        // Désactiver l'action "new" (création de client)
        $actions->disable(Action::NEW);

        // Optionnel : Désactiver d'autres actions si nécessaire
        // $actions->disable(Action::DELETE); // Pour désactiver la suppression

        return $actions;
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}