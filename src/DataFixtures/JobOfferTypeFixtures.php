<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Client;
use App\Entity\ContractType;
use App\Entity\JobOfferType;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class JobOfferTypeFixtures extends Fixture implements DependentFixtureInterface
{
    public const JOB_OFFERS = [
        // Technology
        ['title' => 'Senior Frontend Developer', 'category' => 'technology', 'type' => 'full_time', 'client' => 'recruiter', 'location' => 'Paris', 'salary' => 65000 , 'slug' => 'Technology'],
        ['title' => 'Backend Engineer', 'category' => 'technology', 'type' => 'full_time', 'client' => 'recruiter', 'location' => 'Remote', 'salary' => 70000 , 'slug' => 'Technology'],
        ['title' => 'DevOps Engineer', 'category' => 'technology', 'type' => 'full_time', 'client' => 'recruiter2', 'location' => 'Lyon', 'salary' => 60000 , 'slug' => 'Technology'],
        
        // Fashion & Luxury
        ['title' => 'Fashion Designer', 'category' => 'fashion___luxury', 'type' => 'full_time', 'client' => 'recruiter3', 'location' => 'Paris', 'salary' => 55000 , 'slug' => 'Fashion'],
        ['title' => 'Luxury Brand Manager', 'category' => 'fashion___luxury', 'type' => 'full_time', 'client' => 'recruiter3', 'location' => 'Milan', 'salary' => 75000 , 'slug' => 'Fashion'],
        ['title' => 'Visual Merchandiser', 'category' => 'fashion___luxury', 'type' => 'part_time', 'client' => 'recruiter4', 'location' => 'Nice', 'salary' => 35000 , 'slug' => 'Fashion'],
        
        // Marketing & PR
        ['title' => 'Digital Marketing Manager', 'category' => 'marketing___pr', 'type' => 'full_time', 'client' => 'recruiter5', 'location' => 'Paris', 'salary' => 58000 , 'slug' => 'Marketing'],
        ['title' => 'PR Specialist', 'category' => 'marketing___pr', 'type' => 'freelance', 'client' => 'recruiter5', 'location' => 'Remote', 'salary' => 400 , 'slug' => 'Marketing'],
        ['title' => 'Social Media Manager', 'category' => 'marketing___pr', 'type' => 'part_time', 'client' => 'recruiter6', 'location' => 'Bordeaux', 'salary' => 32000, 'slug' => 'Marketing'] ,
        
        // Commercial
        ['title' => 'Sales Director', 'category' => 'commercial', 'type' => 'full_time', 'client' => 'recruiter7', 'location' => 'Paris', 'salary' => 90000 , 'slug' => 'Commercial'],
        ['title' => 'Business Development Manager', 'category' => 'commercial', 'type' => 'full_time', 'client' => 'recruiter7', 'location' => 'Lyon', 'salary' => 65000 , 'slug' => 'Commercial'],
        ['title' => 'Key Account Manager', 'category' => 'commercial', 'type' => 'full_time', 'client' => 'recruiter8', 'location' => 'Marseille', 'salary' => 55000, 'slug' => 'Commercial'],
        
        // Retail Sales
        ['title' => 'Boutique Manager', 'category' => 'retail_sales', 'type' => 'full_time', 'client' => 'recruiter9', 'location' => 'Cannes', 'salary' => 45000, 'slug' => 'Retail'],
        ['title' => 'Senior Sales Associate', 'category' => 'retail_sales', 'type' => 'full_time', 'client' => 'recruiter9', 'location' => 'Monaco', 'salary' => 35000, 'slug' => 'Retail'],
        ['title' => 'Luxury Retail Advisor', 'category' => 'retail_sales', 'type' => 'seasonal', 'client' => 'recruiter10', 'location' => 'Saint-Tropez', 'salary' => 30000, 'slug' => 'Retail'],
        
        // Creative
        ['title' => 'Creative Director', 'category' => 'creative', 'type' => 'full_time', 'client' => 'recruiter', 'location' => 'Paris', 'salary' => 85000, 'slug' => 'Creative'],
        ['title' => 'Art Director', 'category' => 'creative', 'type' => 'freelance', 'client' => 'recruiter2', 'location' => 'Remote', 'salary' => 500, 'slug' => 'Creative'],
        ['title' => 'UI/UX Designer', 'category' => 'creative', 'type' => 'full_time', 'client' => 'recruiter3', 'location' => 'Lyon', 'salary' => 50000, 'slug' => 'Creative'],
        
        // Management & HR
        ['title' => 'HR Director', 'category' => 'management___hr', 'type' => 'full_time', 'client' => 'recruiter4', 'location' => 'Paris', 'salary' => 80000, 'slug' => 'Management'],
        ['title' => 'Talent Acquisition Manager', 'category' => 'management___hr', 'type' => 'full_time', 'client' => 'recruiter5', 'location' => 'Toulouse', 'salary' => 55000, 'slug' => 'Management'],
        ['title' => 'HR Business Partner', 'category' => 'management___hr', 'type' => 'part_time', 'client' => 'recruiter6', 'location' => 'Lille', 'salary' => 45000, 'slug' => 'Management'],
        
        // Additional positions
        ['title' => 'Luxury Hotel Manager', 'category' => 'management___hr', 'type' => 'full_time', 'client' => 'recruiter7', 'location' => 'Nice', 'salary' => 70000, 'slug' => 'Additional'],
        ['title' => 'Private Chef', 'category' => 'creative', 'type' => 'full_time', 'client' => 'recruiter8', 'location' => 'Monaco', 'salary' => 65000, 'slug' => 'Additional'],
        ['title' => 'Yacht Steward/Stewardess', 'category' => 'retail_sales', 'type' => 'seasonal', 'client' => 'recruiter9', 'location' => 'Antibes', 'salary' => 40000, 'slug' => 'Additional'],
        ['title' => 'Private Jet Cabin Crew', 'category' => 'retail_sales', 'type' => 'full_time', 'client' => 'recruiter10', 'location' => 'Nice', 'salary' => 45000, 'slug' => 'Additional'],
        ['title' => 'Luxury Car Sales Specialist', 'category' => 'commercial', 'type' => 'full_time', 'client' => 'recruiter', 'location' => 'Paris', 'salary' => 60000, 'slug' => 'Additional'],
        ['title' => 'Fine Jewelry Expert', 'category' => 'retail_sales', 'type' => 'full_time', 'client' => 'recruiter2', 'location' => 'Cannes', 'salary' => 55000, 'slug' => 'Additional'],
        ['title' => 'Wine & Spirits Consultant', 'category' => 'commercial', 'type' => 'part_time', 'client' => 'recruiter3', 'location' => 'Bordeaux', 'salary' => 35000, 'slug' => 'Additional'],
        ['title' => 'Luxury Event Planner', 'category' => 'marketing___pr', 'type' => 'freelance', 'client' => 'recruiter4', 'location' => 'Paris', 'salary' => 450, 'slug' => 'Additional'],
        ['title' => 'Personal Shopper', 'category' => 'retail_sales', 'type' => 'full_time', 'client' => 'recruiter5', 'location' => 'Paris', 'salary' => 40000, 'slug' => 'Additional'],
        ['title' => 'Brand Ambassador', 'category' => 'marketing___pr', 'type' => 'temporary', 'client' => 'recruiter6', 'location' => 'Multiple', 'salary' => 35000, 'slug' => 'Additional'],
        ['title' => 'Interior Designer', 'category' => 'creative', 'type' => 'full_time', 'client' => 'recruiter7', 'location' => 'Paris', 'salary' => 50000, 'slug' => 'Additional']
    ];

    public function load(ObjectManager $manager): void
    {
        $slugger = new AsciiSlugger();
        
        foreach (self::JOB_OFFERS as $index => $offerData) {
            $offer = new JobOfferType();
            
            // Données de base
            $offer->setTitle($offerData['title']);
            $offer->setDescription($this->generateDescription($offerData['title']));
            $offer->setActive(true);
            $offer->setLieu($offerData['location']);
            $offer->setSalary($offerData['salary']);
            $offer->setSlug($offerData['slug']);
            $offer->setName($offerData['title']);
           
            
            // Dates
            $offer->setCreatedAt(new DateTimeImmutable('now'));
            $offer->setStartingDate(new DateTimeImmutable('+30 days'));
            
            // Références aux autres entités
            $offer->addCategory($this->getReference('category_' . $offerData['category'], Category::class));
            $offer->setClient($this->getReference('client_' . $offerData['client'], Client::class));
            
            // Générer la référence unique
            // $offer->setReference(sprintf('JOB-%s-%04d', (new DateTimeImmutable())->format('Ymd'), $index + 1));
            
            // Générer le slug
            // $slug = $slugger->slug(strtolower($offerData['title']));
            // $offer->setSlug($slug . '-' . substr(uniqid(), -6));
            
            $manager->persist($offer);
        }

        $manager->flush();
    }

    private function generateDescription(string $title): string
    {
        return "We are currently seeking a highly qualified $title to join our prestigious team. 
        The ideal candidate will have extensive experience in luxury services and a proven track record of excellence. 
        This position offers an exciting opportunity to work with premium brands and high-net-worth clients in an 
        international environment. The role requires excellent communication skills, attention to detail, and a deep 
        understanding of luxury market standards. Join us in delivering exceptional experiences to our discerning clientele.";
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            ClientFixtures::class,
        ];
    }
}