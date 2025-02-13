<?php
namespace App\Controller\Client;

use App\Entity\JobOfferType;
use App\Entity\Client;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class JobOfferTypeCrudController extends AbstractCrudController
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
        return JobOfferType::class;
    }

    public function createIndexQueryBuilder($entityClass, $sortDirection, $sortField = null, $filters = null): QueryBuilder
    {
        // Récupérer l'utilisateur connecté
        $user = $this->security->getUser();

        // Récupérer le repository de l'entité Client
        $clientRepository = $this->entityManager->getRepository(Client::class);

        // Trouver le client associé à l'utilisateur
        $client = $clientRepository->findOneBy(['user' => $user]);

        // Créer le QueryBuilder de base
        $queryBuilder = parent::createIndexQueryBuilder($entityClass, $sortDirection, $sortField, $filters);

        // Filtrer les JobOfferType par client_id
        if ($client) {
            $queryBuilder
                ->andWhere('entity.client = :client')
                ->setParameter('client', $client);
        }

        return $queryBuilder;
    }
}