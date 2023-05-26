<?php

namespace App\DataFixtures;

use App\Entity\Persona;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $emi;
    private $faker;
    private $passHasher;

    public function __construct(UserPasswordHasherInterface $passHasher, EntityManagerInterface $emi)
    {
        $this->emi = $emi;
        $this->faker = Factory::create("fr_FR");
        $this->passHasher = $passHasher;
        
    }
    public function load(ObjectManager $manager): void
    {
        $personaRepo = $this->emi->getRepository(Persona::class);

        $personas = $personaRepo->findAll();
        $plainTextPassword = "pass321";

        for ($i = 40; $i < 50; $i++) { 
            $user = new User();
            $hashedPassword = $this->passHasher->hashPassword($user, $plainTextPassword);
            
            $user->setPersona($personas[$i]);
            $user->setEmail($this->faker->freeEmail());
            $user->setRoles([]);
            $user->setPassword($hashedPassword);
            $user->setFoto($this->faker->imageUrl(640,480,null));
            $user->setNumAbogado($this->faker->unique()->numerify('AP######'));
            $user->setTipo($this->faker->randomElement(['Admin','Abogado','Suplente']));
            $this->addReference("user_" . $this->faker->unique()->randomDigit, $user);
            
            $manager->persist($user);
        }

        $manager->flush();
    }
}
