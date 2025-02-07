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

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cv', FileType::class,[
                'label' => false,
                'required' => false, 
                
            ])
            ->add('firstname',null, [
                'required' => false, // Champ non requis
            ])
            ->add('lastname',null, [
                'required' => false, // Champ non requis
            ])
            ->add('country',null, [
                'required' => false, // Champ non requis
            ])
            ->add('profil_picture', FileType::class,[
                'label' => false,
                'required' => false,
            ])
            ->add('description',null, [
                'required' => false, // Champ non requis
            ])
            ->add('birthplace',null, [
                'required' => false, // Champ non requis
            ])
            ->add('birthdate', DateType::class,[
                'widget' => 'single_text',
                'label' => false,
                'required' => false,
                'empty_data' => null, 
            ])
            ->add('passport_file', FileType::class,[
                'label' => false,
                'required' => false,
            ])
            ->add('current_location',null, [
                'required' => false, // Champ non requis
            ])
            ->add('adress',null, [
                'required' => false, // Champ non requis
            ])
            ->add('nationality',null, [
                'required' => false, // Champ non requis
            ])
          
            ->add('gender', EntityType::class, [
                'class' => Gender::class,
                'choice_label' => 'name',
                'label' => false,
                'required' => false,
            ])
            // ->add('passport_state', EntityType::class, [
            //     'class' => PassportState::class,
            //     'choice_label' => 'id',
            //     'required' => false,
            // ])
            ->add('experience', EntityType::class, [
                'class' => Experience::class,
                'choice_label' => 'level',
                'label' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }
}
