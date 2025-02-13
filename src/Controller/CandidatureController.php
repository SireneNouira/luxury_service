<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Candidature;
use App\Entity\JobOfferType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CandidatureController extends AbstractController

{
    #[Route('/candidature', name: 'app_candidature')]
    public function index(): Response
    {
        return $this->render('candidature/index.html.twig', [
            'controller_name' => 'CandidatureController',
        ]);
    }


    // #[Route('/apply/{jobId}', name: 'apply_job', methods: ['POST'])]
    // public function apply(int $jobId, EntityManagerInterface $em, Request $request): Response
    // {
    //     /** @var User */
    //     $user = $this->getUser();
    //     /** @var Candidate */
    //     $candidate = $user->getCandidate();

    //     if (!$user instanceof Candidate) {
    //         return $this->json(['message' => 'Vous devez être un candidat pour postuler.'], Response::HTTP_FORBIDDEN);
    //     }

    //     if (!$candidate->isValide()) {
    //         return $this->json(['message' => 'Vous devez avoir rempli votre profil candidat a 100% pour postuler.'], Response::HTTP_FORBIDDEN);
    //     }

    //     $job = $em->getRepository(JobOfferType::class)->find($jobId);

    //     if (!$job) {
    //         return $this->json(['message' => 'Offre d\'emploi non trouvée.'], Response::HTTP_NOT_FOUND);
    //     }

    //     $candidature = new Candidature();
    //     $candidature->setCandidat($candidate);
    //     $candidature->setJob($job);

    //     $em->persist($candidature);
    //     $em->flush();

    //     // return $this->json(['message' => 'Candidature soumise avec succès.'], Response::HTTP_CREATED);
    //     $this->addFlash('success', 'Candidature soumise avec succès.');

    //     return $this->redirectToRoute('app_home');
    // }

    #[Route('/apply/{jobId}', name: 'apply_job', methods: ['POST'])]
public function apply(int $jobId, EntityManagerInterface $em, Request $request): JsonResponse
{
    /** @var User */
    $user = $this->getUser();
    /** @var Candidate */
    $candidate = $user->getCandidate();

    if (!$candidate->isValide()) {
        return $this->json(['message' => 'Vous devez avoir rempli votre profil candidat à 100% pour postuler.', 'success' => false], Response::HTTP_FORBIDDEN);
    }

    $job = $em->getRepository(JobOfferType::class)->find($jobId);

    if (!$job) {
        return $this->json(['message' => 'Offre d\'emploi non trouvée.', 'success' => false], Response::HTTP_NOT_FOUND);
    }

    // Vérifier si le candidat a déjà postulé à cette offre
    $existingCandidature = $em->getRepository(Candidature::class)->findOneBy([
        'candidat' => $candidate,
        'job' => $job,
    ]);

    if ($existingCandidature) {
        return $this->json(['message' => 'Vous avez déjà postulé à cette offre.', 'success' => false], Response::HTTP_CONFLICT);
    }

    $candidature = new Candidature();
    $candidature->setCandidat($candidate);
    $candidature->setJob($job);

    $em->persist($candidature);
    $em->flush();

    return $this->json(['message' => 'Candidature soumise avec succès.', 'success' => true], Response::HTTP_CREATED);
}

}
