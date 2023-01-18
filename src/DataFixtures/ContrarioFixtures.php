<?php

namespace App\DataFixtures;

use App\Entity\Contrario;
use App\Entity\Persona;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ContrarioFixtures extends Fixture
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

        for ($i = 10; $i < 20; $i++) { 
            $contrario = new Contrario();
            $contrario->setTipo($this->faker->randomElement(['A','N','J']));
            $contrario->setPersona($personas[$i]);
            $this->addReference("contrario_" . $this->faker->unique()->randomDigit(), $contrario);

            $manager->persist($contrario);
        }

        $manager->flush();
    }
}
