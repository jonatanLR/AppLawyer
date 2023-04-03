<?php

namespace App\DataFixtures;

use App\Entity\TipoCC;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TipoCCFixtures extends Fixture
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
        $tpCCArray = array("Juridica", "Natural", "Privada", "Publica", "Independiente");

        for ($i=0; $i < count($tpCCArray) ; $i++) { 
            $tipoc = new TipoCC();
            $tipoc->setNombre($tpCCArray[$i]);

            $manager->persist($tipoc);
        }

        $manager->flush();
    }
}
