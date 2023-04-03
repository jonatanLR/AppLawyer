<?php

namespace App\DataFixtures;

use App\Entity\Cliente;
use App\Entity\Persona;
use App\Entity\TipoCC;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\DataFixtures\TipoCCFixtures;

class ClienteFixtures extends Fixture
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
        $tipoC = $this->emi->getRepository(TipoCC::class)->findAll();

        $personas = $personaRepo->findAll();

        for ($i = 20; $i < 30; $i++) { 
            $cliente = new Cliente();
            $cliente->setPersona($personas[$i]);
            // $cliente->setTipoc($tipoC[2]);
            $this->addReference("cliente_" . $this->faker->unique()->randomDigit, $cliente);

            $manager->persist($cliente);
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
