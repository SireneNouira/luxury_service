<?php
namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $candidate = $entityManager->getRepository(Candidate::class)->findOneBy(['user' => $user]);
        if (!$candidate) {
            $candidate = new Candidate();
            $candidate->setUser($user);
        }

        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$candidate->getCreatedAt()) {
                $candidate->setCreatedAt(new \DateTimeImmutable());
            }
            $candidate->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($candidate);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully.');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}