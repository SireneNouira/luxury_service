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

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            ])
            // ->add('country', null, [
            //     'required' => false, // Champ non requis
            // ])
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
            ->add('description', null, [
                'required' => false, // Champ non requis
            ])
            ->add('birthplace', null, [
                'required' => false, // Champ non requis
            ])
            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'label' => false,
                'required' => false,
                'empty_data' => null,
            ])
            ->add('passport_file', FileType::class, [
                'label' => false,
                'required' => false,
            ])
            
            ->add('adress', null, [
                'required' => false, // Champ non requis
            ])
            ->add('nationality', null, [
                'required' => false, // Champ non requis
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
            ->addEventListener(FormEvents::POST_SUBMIT, $this->setUpdatedAt(...))
            // ->add('passport_state', EntityType::class, [
            //     'class' => PassportState::class,
            //     'choice_label' => 'id',
            //     'required' => false,
            // ])
            // ->add('experience', EntityType::class, [
            //     'class' => Experience::class,
            //     'choice_label' => 'level',
            //     'label' => false,
            //     'required' => false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
    private function setUpdatedAt(FormEvent $event): void
    {
        $candidate = $event->getData();
        $candidate->setUpdatedAt(new DateTimeImmutable());
    }
}
