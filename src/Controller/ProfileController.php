<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Form\CandidateType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FileUploadError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


final class ProfileController extends AbstractController
{
    private FileUploader $fileUploader;
    private UserPasswordHasherInterface $passwordHasher;


    public function __construct(FileUploader $fileUploader,   UserPasswordHasherInterface $passwordHasher)
    {
        $this->fileUploader = $fileUploader;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager, Request $request, FileUploader $fileUploader): Response
    {

        $this->fileUploader = $fileUploader;
        
        /** @var User */
        $user = $this->getUser();

        $candidate = $user->getCandidate();

        if(!$candidate){
            $candidate = new Candidate();
            $candidate->setUser($user);
            $entityManager->persist($candidate);
            $entityManager->flush();
        }

        if(!$user->isVerified())
        {
            return $this->render('errors/not-verified.html.twig', [
            
            ]);
        }

        $formCandidate = $this->createForm(CandidateType::class, $candidate);
        $formCandidate->handleRequest($request);

        if($formCandidate->isSubmitted() && $formCandidate->isValid()){
            // dd($candidate);
            $profilPictureFile = $formCandidate->get('profilePicture')->getData();
            // dd($profilPictureFile);

            if($profilPictureFile){
                $profilPictureName = $fileUploader->upload($profilPictureFile, $candidate, 'profilePicture', 'profile_pictures');
                $candidate->setProfilePicture($profilPictureName);
            }


            $cvFile = $formCandidate->get('cv')->getData();

            if($cvFile){
                $cvFileName = $fileUploader->upload($cvFile, $candidate, 'cv', 'cvs');
                $candidate->setCv($cvFileName);
            }




            $password = $formCandidate->get('password')->getData();
            $passwordRepeat = $formCandidate->get('password_repeat')->getData();
            
            if ($password && $password !== $passwordRepeat) {
                $this->addFlash('error', 'Passwords do not match.');
                return $this->redirectToRoute('app_profile');
            }
            
            if ($password) {
                $encodedPassword = $this->passwordHasher->hashPassword($user, $password);
                $user->setPassword($encodedPassword);
            }
            
            
            $entityManager->persist($candidate);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully');
        }

        


        return $this->render('profile/index.html.twig', [
            'form' => $formCandidate->createView(),
            'candidate' => $candidate,
            'user' => $user,
        
        ]);
    }
}