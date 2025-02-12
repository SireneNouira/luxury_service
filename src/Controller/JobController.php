<?php
namespace App\Controller;

use App\Repository\JobOfferTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
final class JobController extends AbstractController
{
    #[Route('/job', name: 'app_job')]
    public function index(JobOfferTypeRepository $JobOfferTypeRepository): Response
    {
        $jobOfferType = $JobOfferTypeRepository->findAll();
        return $this->render('job/index.html.twig', [
         'jobs' => $jobOfferType,
        ]);
    }
    #[Route('/job/{slug}', name: 'app_job_show')]
    public function show(string $slug, JobOfferTypeRepository $JobOfferTypeRepository): Response
    {
        $job = $JobOfferTypeRepository->findOneBy(['slug' => $slug]);

        
        if (!$job) {
            throw $this->createNotFoundException('Job non trouvé');
        }
    
        return $this->render('job/show.html.twig', [
            'job' => $job,
        ]);
    }
}
