<?php

namespace App\Controller;

use App\Entity\JobOfferType;
use App\Repository\CategoryRepository;
use App\Repository\JobOfferTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(JobOfferTypeRepository $JobOfferTypeRepository): Response
    {

        $jobOfferType = $JobOfferTypeRepository->findBy([], ['id' => 'DESC'], 10);
    
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'jobs' => array_reverse($jobOfferType),
        ]);
    }
}