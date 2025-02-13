<?php

namespace App\Controller\Client;

use App\Entity\Candidate;
use App\Entity\Candidature;
use App\Entity\Client;
use App\Entity\JobOfferType;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

class CandidatureCrudController extends AbstractCrudController
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
        return Candidature::class;
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

        // Si un client est trouvé, filtrer les candidatures par ses offres d'emploi
        if ($client) {
            // Récupérer les IDs des offres d'emploi du client
            $jobOfferIds = $this->entityManager->getRepository(JobOfferType::class)
                ->createQueryBuilder('j')
                ->select('j.id')
                ->where('j.client = :client')
                ->setParameter('client', $client)
                ->getQuery()
                ->getResult();

            // Extraire les IDs des résultats
            $jobOfferIds = array_column($jobOfferIds, 'id');

            // Filtrer les candidatures par les IDs des offres d'emploi
            $queryBuilder
                ->andWhere('entity.job IN (:jobOfferIds)')
                ->setParameter('jobOfferIds', $jobOfferIds);
        }

        return $queryBuilder;
    }


    public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(), // Masquer l'ID dans le formulaire
        AssociationField::new('candidat', 'Candidate')
            ->setLabel('Candidate')
            ->formatValue(function ($value, $entity) {
                // Afficher le nom complet du candidat
                /** @var Candidate $candidat */
                $candidat = $entity->getCandidat();
                return $candidat ? $candidat->getFirstName() . ' ' . $candidat->getLastName() : 'N/A';
            })
            ->setCustomOption('link', function ($entity) {
                // Générer un lien vers la page de détail du candidat
                $candidat = $entity->getCandidat();
                if ($candidat) {
                    return $this->container->get(AdminUrlGenerator::class)
                        ->setController(CandidateCrudController::class)
                        ->setAction(Action::DETAIL) 
                        ->setEntityId($candidat->getId())
                        ->generateUrl();
                }
                return null;
            }),
        AssociationField::new('job', 'Job')
            ->setLabel('Job')
            ->formatValue(function ($value, $entity) {
                // Afficher le titre du job
                $job = $entity->getJob();
                return $job ? $job->getTitle() : 'N/A';
            }),
        // Ajoutez d'autres champs si nécessaire
    ];
}
    public function configureActions(Actions $actions): Actions
    {
        // Désactiver l'action "new" (création de client)
        $actions->disable(Action::NEW);
        // $actions->disable(Action::EDIT);
        // $actions->disable(Action::DELETE);

        // Activer l'action "show" (affichage des détails du client)
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
     

        return $actions;
    }
}
