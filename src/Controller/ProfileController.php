<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\User;
use App\Form\CandidateType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ProfileProgressionCalculator;


final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        EntityManagerInterface $entityManager,
        Request $request,
        FileUploader $fileUploader,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer,
        ProfileProgressionCalculator $profileProgressionCalculator
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $candidate = $user->getCandidate();

        if (!$user->isVerified()) {
            return $this->render('errors/not-verified.html.twig', []);
        }

        if (!$candidate) {
            $candidate = new Candidate();
            $candidate->setUser($user);
            $entityManager->persist($candidate);
            $entityManager->flush();
        }

        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $profilePictureFile */
            $profilePictureFile = $form->get('profilPictureFile')->getData();

            /** @var UploadedFile $passportFile */
            $passportFile = $form->get('passportFile')->getData();

            /** @var UploadedFile $cvFile */
            $cvFile = $form->get('cvFile')->getData();

             // this condition is needed because the 'profilePicture' field is not required
            // so the file must be processed only when a file is uploaded
            if ($profilePictureFile) {
                $profilePictureName = $fileUploader->upload($profilePictureFile, $candidate, 'profilePicture', 'profile-pictures');
                $candidate->setProfilePicture($profilePictureName);
            }

            // this condition is needed because the 'passportFile' field is not required
            // so the file must be processed only when a file is uploaded
            if ($passportFile) {
                $passportName = $fileUploader->upload($passportFile, $candidate, 'passport', 'passport');
                $candidate->setPassport($passportName);
            }

            // this condition is needed because the 'cvFile' field is not required
            // so the file must be processed only when a file is uploaded
            if ($cvFile) {
                $cvName = $fileUploader->upload($cvFile, $candidate, 'cv', 'curriculum-vitae');
                $candidate->setCv($cvName);
            }

            $email = $form->get('email')->getData();
            $newPassword = $form->get('newPassword')->getData();

            if ($email || $newPassword) {
                if ($email && $newPassword) {
                    if ($user->getEmail() !== $email) {
                        $this->addFlash('danger', 'The email you entered does not match the email associated with your account.');
                    } else {
                        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                        $user->setPassword($hashedPassword);
                        try {
                            $mail = (new TemplatedEmail())
                                ->from('support@luxury-services.com')
                                ->to($user->getEmail())
                                ->subject('Change of password')
                                ->htmlTemplate('emails/change-password.html.twig');         
            
                            $mailer->send($mail);
                            $this->addFlash('success', 'Your password has been changed successfully!');
                        } catch (\Exception $e) {
                            $this->addFlash('danger', 'An error occurred while sending the message : ' . $e->getMessage());
                        }
                    }
                } else {
                    $this->addFlash('danger', 'Email and password must be filled together to change password.');
                }
            }

            $profileProgressionCalculator->calculateProgress($candidate);
            
            $entityManager->persist($candidate);
            $entityManager->flush();

            $this->addFlash('success', 'Profile updated successfully');

            return $this->redirectToRoute('app_profile');
        }

        if ($candidate->getProfilePicture()) {
            $originalProfilePictureFilename = preg_replace('/-\w{13}(?=\.\w{3,4}$)/', '', $candidate->getProfilePicture());
        }

        if ($candidate->getPassport()) {
            $originalPassportFilename = preg_replace('/-\w{13}(?=\.\w{3,4}$)/', '', $candidate->getPassport());
        }

        if ($candidate->getCv()) {
            $originalCvFilename = preg_replace('/-\w{13}(?=\.\w{3,4}$)/', '', $candidate->getCv());
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
            'candidate' => $candidate,
            'originalProfilPicture' => $originalProfilePictureFilename ?? null,
            'originalPassport' => $originalPassportFilename ?? null,
            'originalCv' => $originalCvFilename ?? null,
        ]);
    }
}