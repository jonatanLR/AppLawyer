<?php

namespace App\DataFixtures;

use App\Entity\Persona;
use App\Entity\Procurador;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProcuradorFixtures extends Fixture
{
    private $emi;
    private $faker;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
        $this->faker = Factory::create("fr_FR");
        
    }
    public function load(ObjectManager $manager): void
    {
        $personaRepo = $this->emi->getRepository(Persona::class);

        $personas = $personaRepo->findAll();

        for ($i = 30; $i < 40; $i++) { 
            $procurador = new Procurador();
            $procurador->setNumAbogado($this->faker->unique()->numerify('AP######'));
            $procurador->setPersona($personas[$i]);
            $manager->persist($procurador);
        }

        $manager->flush();
    }
}
