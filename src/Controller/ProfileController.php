<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\Category;
use App\Entity\JobOfferType;
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
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class ProfileController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CsrfTokenManagerInterface $csrfTokenManager;
   

    public function __construct(EntityManagerInterface $entityManager, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->entityManager = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
    }

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
            $jobCategory = $form->get('jobCategory')->getData();
            if ($jobCategory instanceof JobOfferType) {
                $candidate->setJobCategory($jobCategory);
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



 /**
 * @Route("/delete-account", name="delete_account", methods={"POST"})
 */
public function deleteAccount(Request $request): Response
{
    // Vérifier le CSRF token
    $token = new CsrfToken('delete_account', $request->request->get('_token'));

    if (!$this->csrfTokenManager->isTokenValid($token)) {
        throw $this->createAccessDeniedException('Invalid CSRF token.');
    }

    // Récupérer l'utilisateur actuellement connecté
    $user = $this->getUser();

    if (!$user instanceof User) {
        throw $this->createAccessDeniedException('Invalid user type.');
    }

    // Récupérer l'entité Candidate associée à l'utilisateur
    $candidate = $user->getCandidate();

    if ($candidate) {
        // Mettre à jour le champ deletedAt de l'entité Candidate
        $candidate->setDeletedAt(new \DateTimeImmutable());
        $candidate->setUser(null);
        $this->entityManager->persist($candidate);
    }

    // Déconnecter l'utilisateur avant de le supprimer
    $this->container->get('security.token_storage')->setToken(null);
    $request->getSession()->invalidate();

    // Supprimer l'utilisateur
    $this->entityManager->remove($user);
    $this->entityManager->flush();

    // Rediriger vers la page d'accueil
    return $this->redirectToRoute('app_home');
}

}