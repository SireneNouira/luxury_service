<?php
namespace App\Service;


use App\Entity\Candidate;
use Doctrine\ORM\EntityManagerInterface;

class ProgressionService
{
    private array $fieldsToIgnore = ['email']; // Champs ignorés
    private array $optionalFields = ['passport_file','passport_file']; // Champs optionnels qui ne diminuent pas la progression

    public function __construct(private EntityManagerInterface $entityManager) {}

    public function calculateProgression(Candidate $candidate): int
    {
        $reflection = new \ReflectionClass(Candidate::class);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);

        $totalFields = 0;
        $filledFields = 0;

        foreach ($properties as $property) {
            $fieldName = $property->getName();
            if (in_array($fieldName, $this->fieldsToIgnore)) {
                continue; // Ignore les champs non pris en compte
            }

            $totalFields++;
            $property->setAccessible(true); // Permet d'accéder aux propriétés privées
            $value = $property->getValue($candidate);
                        if (!empty($value) && !in_array($fieldName, $this->optionalFields)) {
                $filledFields++;
            }
        }

        $progress = $totalFields > 0 ? (int) round(($filledFields / $totalFields) * 100) : 0;
        $candidate->setProgression($progress);

        $this->entityManager->persist($candidate);
        $this->entityManager->flush();

        return $progress;
    }
}
