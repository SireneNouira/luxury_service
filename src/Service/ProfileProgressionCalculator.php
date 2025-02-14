<?php

namespace App\Service;

use App\Attribute\ProfileField;
use App\Entity\Candidate;
use Countable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionProperty;

class ProfileProgressionCalculator
{
    private static array $profileMappingCache = [];
    private ReflectionClass $reflection;
    private EntityManagerInterface $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
       $this->entityManager = $entityManager; 
    }

    public function calculateProgress(Candidate $candidate): int
    {
        $className = get_class($candidate);
        $this->reflection = new ReflectionClass($candidate);

        if(!isset(self::$profileMappingCache[$className])) {
            self::$profileMappingCache[$className] = $this->getProfileMapping();
        }

        $mapping = self::$profileMappingCache[$className];

        $completedFields = 0;
        $totalFields = count($mapping);

        foreach($mapping as $propertyName){
            $propertyValue = $this->getPropertyValue($candidate, $propertyName);

            if($this->isFieldCompleted($propertyValue)) {
                $completedFields++;
            }
        }

        // eviter la division par 0
        if($totalFields === 0) {
            return 0;
        }

        $progressPercentage = ($completedFields / $totalFields) * 100;
        $progressPercentage = (int) round($progressPercentage);

        $candidate->setCompletionPercentage($progressPercentage);
        $this->entityManager->persist($candidate);
        $this->entityManager->flush();

        return $progressPercentage;

    }

    private function getProfileMapping(): array
    {
        $mapping = [];

        foreach($this->reflection->getProperties() as $property)
        {
            if($this->isProfileField($property)) {
                $mapping[] = $property->getName();
            }
        }

        return $mapping;
    }

    private function isProfileField(ReflectionProperty $property): bool
    {
        $attributes = $property->getAttributes(ProfileField::class);
        return !empty($attributes);
    }

    private function getPropertyValue(Candidate $candidate, string $propertyName): mixed
    {
        $getter = 'get' . ucfirst($propertyName);
        if(method_exists($candidate, $getter)){
            return $candidate->$getter();
        }
        return null;
    }

    private function isFieldCompleted($value): bool
    {
        if($value === null) {
            return false;
        }

        if(is_string($value) && trim($value) === '') {
            return false;
        }

        if(is_array($value) && empty($value)) {
            return false;
        }

        if($value instanceof Countable && count($value) === 0) {
            return false;
        }

        return true;
    }
}