<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\Experience;
use App\Entity\Gender;
use App\Entity\PassportState;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DateTimeImmutable;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Mime\Part\File as PartFile;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $user = $options['user']; // Récupérer l'utilisateur actuel depuis les options du formulaire
        $builder
            ->add('firstName', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'first_name',
                ],
                'label' => 'First name',
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'last_name',
                ],
                'label' => 'Last name',
            ])
            ->add('current_location', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'current_location',
                ],
                'label' => 'Current Location',
            ])
            ->add('adress', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'address',
                ],
                'label' => 'Adress',
            ])
            ->add('profilePicture', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image document',
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg,.jpeg,.png,.gif',
                    'id' => 'photo',
                ]
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'country',
                ],
                'label' => 'Country',
            ])
            ->add('nationality', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'nationality',
                ],
                'label' => 'Nationality',
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => false,
                'empty_data' => null,
                'attr' => [
                    'id' => 'birth_date',
                    'class' => 'form-control',
                ],
                'label' => 'Birth Date',
            ])
            ->add('birthplace', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'birth_place',
                ],
                'label' => 'Birth Place',
            ])
            ->add('description', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'description',
                    'class' => 'materialize-textarea',
                ],
                'label' => 'Description',
            ])
            ->add('passport_file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image document',
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg,.jpeg,.png,.gif',
                    'id' => 'passport',
                    'class' => 'file-path validate',
                ]
            ])
            ->add('gender', EntityType::class, [
                'class' => Gender::class,
                'choice_label' => 'name',
                'label' => false,
                'required' => false,
                'attr' => [
                    'id' => 'gender',
                ],
                'label' => 'Gender',
                'label_attr' => [
                    'class' => 'active',
                ],
                'placeholder' => 'Choose an option...',
            ])
            ->add('experience', EntityType::class, [
                'class' => Experience::class,
                'choice_label' => 'level',
                'required' => false,
                'attr' => [
                    'id' => 'experience',
                    'class' => 'form-control',
                ],
                'label' => 'Experience',
            ])
            ->add('cv', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image document',
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg,.jpeg,.png,.gif',
                    'id' => 'cv',
                    'class' => 'file-path validate',
                ]
            ])
            // ->add('email', EmailType::class, [
            //     'mapped' => false, 
            //     'data' => $user ? $user->getEmail() : '', // Pré-remplir avec l'e-mail de l'utilisateur
            //     'attr' => [
            //         'class' => 'form-control',
            //         'readonly' => true, // Empêcher la modification côté client
            //     ],
            //     'disabled' => true, // Empêcher la soumission du champ
            //     'label' => 'Email', // Libellé du champ
            // ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'password',
                ],
                'label' => 'New Password',
                'required' => false,
            ])
            ->add('password_repeat', PasswordType::class, [
                'mapped' => false,
                'attr' => ['class' => 'form-control', 'id' => 'password_repeat'],
                'label' => 'Confirm New Password',
                'required' => false,
                'constraints' => [new NotBlank(['message' => 'Please confirm your password.'])],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $password = $form->get('password')->getData();
                $passwordRepeat = $form->get('password_repeat')->getData();

                if ($password && $passwordRepeat && $password !== $passwordRepeat) {
                    $form->get('password_repeat')->addError(new FormError('Passwords do not match.'));
                }
            })
            ->addEventListener(FormEvents::POST_SUBMIT, $this->setUpdatedAt(...))
            // ->add('passport_state', EntityType::class, [
            //     'class' => PassportState::class,
            //     'choice_label' => 'id',
            //     'required' => false,
            // ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
            'user' => null, // Ajout de l'option 'user'
        ]);
    }
    private function setUpdatedAt(FormEvent $event): void
    {
        $candidate = $event->getData();
        $candidate->setUpdatedAt(new DateTimeImmutable());
    }
}
