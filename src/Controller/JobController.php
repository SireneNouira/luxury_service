<?php
namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\JobOfferTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
final class JobController extends AbstractController
{
    #[Route('/job', name: 'app_job')]
    public function index(JobOfferTypeRepository $JobOfferTypeRepository, CategoryRepository $CategoryRepository): Response
    {
        $CategoryRepository = $CategoryRepository->findAll();
        $jobOfferType = $JobOfferTypeRepository->findAll();
        return $this->render('job/index.html.twig', [
         'jobs' => $jobOfferType,
         'categories' => $CategoryRepository,
        ]);
    }
    #[Route('/job/{id}', name: 'app_job_show')]
    public function show(string $id, JobOfferTypeRepository $JobOfferTypeRepository): Response
    {
        $job = $JobOfferTypeRepository->findOneBy(['id' => $id]);

        
        if (!$job) {
            throw $this->createNotFoundException('Job non trouvé');
        }
    
    // Récupérer l'annonce précédente et suivante
    $previousJob = $JobOfferTypeRepository->findPreviousJob($job);
    $nextJob = $JobOfferTypeRepository->findNextJob($job);

    return $this->render('job/show.html.twig', [
        'job' => $job,
        'previousJob' => $previousJob,
        'nextJob' => $nextJob,
    ]);
    }

    // #[Route('/job/{slug}/previous', name: 'app_job_previous')]
    // public function previous(string $slug, JobOfferTypeRepository $JobOfferTypeRepository): Response
    // {
    //     $job = $JobOfferTypeRepository->findOneBy(['slug' => $slug]);
    //     if (!$job) {
    //         throw $this->createNotFoundException('Job non trouvé');
    //     }

    //     $previousJob = $JobOfferTypeRepository->findPreviousJob($job);
    //     if (!$previousJob) {
    //         throw $this->createNotFoundException('Aucune annonce précédente trouvée');
    //     }

    //     return $this->redirectToRoute('app_job_show', ['slug' => $previousJob->getSlug()]);
    // }

    // #[Route('/job/{slug}/next', name: 'app_job_next')]
    // public function next(string $slug, JobOfferTypeRepository $JobOfferTypeRepository): Response
    // {
    //     $job = $JobOfferTypeRepository->findOneBy(['slug' => $slug]);
    //     if (!$job) {
    //         throw $this->createNotFoundException('Job non trouvé');
    //     }

    //     $nextJob = $JobOfferTypeRepository->findNextJob($job);
    //     if (!$nextJob) {
    //         throw $this->createNotFoundException('Aucune annonce suivante trouvée');
    //     }

    //     return $this->redirectToRoute('app_job_show', ['slug' => $nextJob->getSlug()]);
    // }
    
    #[Route('/job/{id}/previous', name: 'app_job_previous', requirements: ['id' => '\d+'])]
public function previous(int $id, JobOfferTypeRepository $JobOfferTypeRepository): Response
{
    $job = $JobOfferTypeRepository->find($id);
    if (!$job) {
        throw $this->createNotFoundException('Job non trouvé');
    }

    $previousJob = $JobOfferTypeRepository->findPreviousJob($job);
    if (!$previousJob) {
        throw $this->createNotFoundException('Aucune annonce précédente trouvée');
    }

    return $this->redirectToRoute('app_job_show', ['id' => $previousJob->getId()]);
}

#[Route('/job/{id}/next', name: 'app_job_next', requirements: ['id' => '\d+'])]
public function next(int $id, JobOfferTypeRepository $JobOfferTypeRepository): Response
{
    $job = $JobOfferTypeRepository->find($id);
    if (!$job) {
        throw $this->createNotFoundException('Job non trouvé');
    }

    $nextJob = $JobOfferTypeRepository->findNextJob($job);
    if (!$nextJob) {
        throw $this->createNotFoundException('Aucune annonce suivante trouvée');
    }

    return $this->redirectToRoute('app_job_show', ['id' => $nextJob->getId()]);
}

}
