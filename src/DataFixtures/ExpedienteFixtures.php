<?php

namespace App\DataFixtures;

use App\Entity\Contrario;
use App\Entity\Expediente;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Procurador;
use App\Entity\Juzgado;
use App\Entity\TpProcedimiento;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

class ExpedienteFixtures extends Fixture implements DependentFixtureInterface
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
        $procurRepos = $this->emi->getRepository(Procurador::class);
        $juzgadoRepos = $this->emi->getRepository(Juzgado::class);
        $tpProcedRepos = $this->emi->getRepository(TpProcedimiento::class);

        $procuradores = $procurRepos->findAll();
        $juzgados = $juzgadoRepos->findAll();
        $tpPrpocedimientos = $tpProcedRepos->findAll();
        
            for ($i=0; $i < 15; $i++) {   
            
            $expediente = new Expediente();
            $expediente->setId($this->faker->unique()->numerify('EXP' . date('Y') . '0000###'));
            $expediente->setProcurador($procuradores[$this->faker->numberBetween(0,9)]);
            $expediente->setJuzgado($juzgados[$this->faker->numberBetween(0,13)]);
            $expediente->setTpProcedimiento($tpPrpocedimientos[$this->faker->numberBetween(0,3)]);
            $expediente->setTitulo($this->faker->sentence());
            $expediente->setDescripcion($this->faker->paragraph());
            $expediente->setFechaAlta($this->faker->dateTime('now',null));
            $expediente->addJueze($this->getReference('juez_' . $this->faker->numberBetween(0,9)));
            $expediente->addContrario($this->getReference('contrario_' . $this->faker->numberBetween(0,9)));
            $expediente->addCliente($this->getReference('cliente_' . $this->faker->numberBetween(0,9)));
            $expediente->addUser($this->getReference('user_' . $this->faker->numberBetween(0,9)));
            $manager->persist($expediente);
            }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ContrarioFixtures::class,
            ClienteFixtures::class,
            UserFixtures::class,
            ProcuradorFixtures::class
        ];
    }
}
