<?php

namespace App\DataFixtures;

use App\Entity\Actuacion;
use App\Entity\Expediente;
use App\Entity\TpActuacion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class ActuacionFixtures extends Fixture implements DependentFixtureInterface
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
        $tpActuacionRepos = $this->emi->getRepository(TpActuacion::class);
        $tpActuacionArray = $tpActuacionRepos->findAll();

        $expedienteRepos = $this->emi->getRepository(Expediente::class);
        $expedienteArray = $expedienteRepos->findAll();
       
        for ($i=0; $i < 30; $i++) { 
         
        $actuacion = new Actuacion();
        $actuacion->setTpActuacion($this->faker->randomElement($tpActuacionArray));
        $actuacion->setExpediente($this->faker->randomElement($expedienteArray));
        $actuacion->setNombre($this->faker->sentence());
        $actuacion->setFechaAlta($this->faker->dateTime('now',null));
        $actuacion->setDescripcion($this->faker->paragraph());
        $actuacion->setDireccion($this->faker->address());
        $manager->persist($actuacion);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AppFixtures::class,
            ExpedienteFixtures::class
        ];
    }
}
