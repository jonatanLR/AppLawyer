<?php

namespace App\DataFixtures;

use App\Entity\Contrario;
use App\Entity\Persona;
use App\Entity\TipoCC;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\DataFixtures\TipoCCFixtures;

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

        $tpcRepo = $this->emi->getRepository(TipoCC::class);
        $tipoC = $tpcRepo->findAll();

        $personas = $personaRepo->findAll();

        for ($i = 10; $i < 20; $i++) { 
            $contrario = new Contrario();
            $contrario->setPersona($personas[$i]);
            // $contrario->setTipoc($tipoC[2]);
            $this->addReference("contrario_" . $this->faker->unique()->randomDigit(), $contrario);

            $manager->persist($contrario);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TipoCCFixtures::class
        ];
    }
}
