<?php

namespace App\DataFixtures;

use App\Entity\Cliente;
use App\Entity\Persona;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

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

        $personas = $personaRepo->findAll();

        for ($i = 20; $i < 30; $i++) { 
            $cliente = new Cliente();
            $cliente->setTipo($this->faker->randomElement(['P','PT','O']));
            $cliente->setPersona($personas[$i]);
            $this->addReference("cliente_" . $this->faker->unique()->randomDigit, $cliente);

            $manager->persist($cliente);
        }

        $manager->flush();
    }
}
