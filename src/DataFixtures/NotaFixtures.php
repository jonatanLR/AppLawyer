<?php

namespace App\DataFixtures;

use App\Entity\Actuacion;
use App\Entity\Nota;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NotaFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;
    private $emi;

    public function __construct(EntityManagerInterface $emi)
    {
        $this->emi = $emi;
        $this->faker = Factory::create("fr_FR");
    }
    
    public function load(ObjectManager $manager): void
    {
        $actuacionRepos = $this->emi->getRepository(Actuacion::class);
        $actuacionArray = $actuacionRepos->findAll();

        for ($i=0; $i < 10; $i++) { 
            $newNota = new Nota();
            $newNota->setActuacion($this->faker->randomElement($actuacionArray));
            $newNota->setNombre($this->faker->sentence());
            $newNota->setDescripcion($this->faker->text(80));
            $manager->persist($newNota);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ActuacionFixtures::class
        ];
    }
}
