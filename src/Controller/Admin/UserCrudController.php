<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    public function __construct(
        private UserPasswordHasherInterface $passwordhasher
    )
    {
        
    }

 

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Recruter')
            ->setEntityLabelInPlural('Recruters')
            ->setPageTitle(Crud::PAGE_INDEX, 'Recruters')
            ->setPageTitle(Crud::PAGE_NEW, 'Add Recruter')
            ->setPageTitle(Crud::PAGE_EDIT, 'Edit Recruter')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Recruter Details');
        ;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = $this->container->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $queryBuilder->andWhere('entity.roles LIKE :role')
            ->setParameter('role', '%ROLE_CLIENT%');
        return $queryBuilder;
    }

 
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            TextField::new('password', 'Password')
                ->setFormType(RepeatedType::class)
                ->setFormTypeOption('type', PasswordType::class)
                ->setFormTypeOption('first_options', ['label' => 'Password'])
                ->setFormTypeOption('second_options', ['label' => 'Repeat Password'])
                ->setRequired($pageName === Crud::PAGE_NEW)
                ->onlyOnForms()
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityInstance instanceof User){
            $entityInstance->setRoles(['ROLE_CLIENT']);
            $entityInstance->setIsVerified(true);
            $this->hashPassword($entityInstance);

            $client = new Client();
            $client->setUser($entityInstance);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }


    private function hashPassword(User $user): void
    {
        if($user->getPassword()){
            $user->setPassword($this->passwordhasher->hashPassword($user, $user->getPassword()));
        }
    }

}
