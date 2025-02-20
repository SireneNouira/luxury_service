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
    public function index(JobOfferTypeRepository $JobOfferTypeRepository, CategoryRepository $CategoryRepository): Response
    {
    $CategoryRepository = $CategoryRepository->findAll();
    $jobOfferType = $JobOfferTypeRepository->findAll();
     
    
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'jobs' => array_reverse($jobOfferType),
            'categories' => $CategoryRepository,
        ]);
    }
//     #[Route('/', name: 'app_home')]
// public function index(JobOfferTypeRepository $JobOfferTypeRepository, CategoryRepository $CategoryRepository): Response
// {
//     $categories = $CategoryRepository->findAll();
//     $jobOfferTypes = $JobOfferTypeRepository->findBy([], ['id' => 'DESC'], 10);

//     return $this->render('home/index.html.twig', [
//         'controller_name' => 'HomeController',
//         'jobs' => $jobOfferTypes,
//         'categories' => $categories,
//     ]);
// }

}