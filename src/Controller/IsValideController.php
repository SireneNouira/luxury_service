<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Entity\Candidate;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


final class IsValideController extends AbstractController
{
    #[Route('/is/valide', name: 'app_is_valide', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {

        if (!$this->getUser()) {
            return new JsonResponse(['status' => 'error', 'message' => 'User not authenticated'], 401);
        }
        
        $data = json_decode($request->getContent(), true);
        if (isset($data['isValide']) && $data['isValide'] === true) {
           
            /**
            * @var Candidate $candidate
            */
            
            if ($candidate) {
                $candidate->setIsValide(true);
                $entityManager->persist($candidate);
                $entityManager->flush();
                return new JsonResponse(['status' => 'success']);
            }
        }
        return new JsonResponse(['status' => 'error'], 400);
    }
}
