<?php

namespace App\Controller\Client;

use App\Entity\Candidate;
use App\Entity\Candidature;
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

    public function findCandidatByCandidatureId(int $candidat_id, int $job_id): Response
    {
        // Récupérer le repository de l'entité Candidature
        $candidatureRepository = $this->entityManager->getRepository(Candidature::class);
    
        // Trouver la candidature associée à l'ID candidat_id ET job_id
        $candidature = $candidatureRepository->findOneBy([
            'candidat' => $candidat_id,
            'job' => $job_id,
        ]);
    
        if (!$candidature) {
            throw $this->createNotFoundException('Candidature non trouvée pour le candidat ID ' . $candidat_id . ' et le job ID ' . $job_id);
        }
    
        // Récupérer le candidat et le job associés à la candidature
        /** @var Candidate $candidate */
        $candidate = $candidature->getCandidat();
        /** @var JobOfferType $job */
        $job = $candidature->getJob();
    
        if (!$candidate) {
            throw $this->createNotFoundException('Candidat non trouvé pour la candidature avec l\'ID ' . $candidat_id);
        }
    
        if (!$job) {
            throw $this->createNotFoundException('Job non trouvé pour la candidature avec l\'ID ' . $job_id);
        }
    
        // Retourner une réponse JSON avec les informations du candidat et du job
        return $this->json([
            'candidat' => [
                'id' => $candidate->getId(),
                'nom' => $candidate->getLastName(),
                'prenom' => $candidate->getFirstName(),
                // Ajoutez d'autres champs si nécessaire
            ],
            'job' => [
                'id' => $job->getId(),
                'titre' => $job->getTitle(),
                'description' => $job->getDescription(),
                // Ajoutez d'autres champs si nécessaire
            ],
        ]);
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
