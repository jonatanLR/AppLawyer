<?php

namespace App\DataFixtures;

use App\Entity\Juez;
use App\Entity\Juzgado;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
use App\Entity\Persona;
use App\Entity\Role;
use App\Entity\TpActuacion;
use App\Entity\TpProcedimiento;
use Doctrine\ORM\EntityManagerInterface;

class AppFixtures extends Fixture
{
    private $faker;
    private $emi;

    public function __construct(UserPasswordHasherInterface $passHasher, EntityManagerInterface $emi) {
        $this->passHasher = $passHasher;
        $this->faker = Factory::create("fr_FR");
        $this->emi = $emi;
    }

    public function load(ObjectManager $manager): void
    {
        
        $this->loadPersona($manager);
        $this->loadJuez($manager);
        $this->loadRole($manager);
        $this->loadJuzgado($manager);
        $this->loadTpProcedimiento($manager);
        $this->loadTpActuacion($manager);
    }

    public function loadPersona(ObjectManager $manager){
        for ($i=0; $i < 50; $i++) { 
            $persona = new Persona();
            $persona->setNombre($this->faker->name());
            $persona->setDni($this->faker->unique()->numerify(rand(0,1) . rand(1,8) . '##-19##-#####'));
            $persona->setEmail($this->faker->email);
            $persona->setDireccion($this->faker->address());
            $persona->setTelefono($this->faker->numerify($this->faker->randomElement([3,8,9]) .'#######'));

            $manager->persist($persona);
        }

        $manager->flush();
    }

    public function loadJuez(ObjectManager $manager){
        $personaRepo = $this->emi->getRepository(Persona::class);

        $personas = $personaRepo->findAll();

        for ($i = 0; $i < 10; $i++) { 
            $juez = new Juez();
            $juez->setNumProfesion($this->faker->unique()->numerify('A######'));
            $juez->setPersona($personas[$i]);
            $this->addReference("juez_" . $this->faker->unique()->randomDigit, $juez);
            $manager->persist($juez);
        }

        $manager->flush();
    }

    public function loadRole(ObjectManager $manager){
        $arrayName = array("Role Admin", "Role Backoffice", "Role Manager", "Role User");
        $arrayRole = array("ROLE_ADMIN", "ROLE_BACKOFFICE", "ROLE_MANAGER", "ROLE_USER");
        for ($i=0; $i < 4; $i++) { 
            $role = new Role();
            $role->setName($arrayName[$i]);
            $role->setRoleName([$arrayRole[$i]]);
            $role->setStatus(1);
            
            $manager->persist($role);
        }

        $manager->flush();
    }

    public function loadJuzgado(ObjectManager $manager){
        $juzgadosArray = array("Juzgado de Letras de Inquilinato", "Juzgado en materia de extorción", "JUZGADO DE NIÑEZ Y JUZGADO DE EJECUCION PENAL", "Juzgado de letras", "Juzgado de Paz", "Corte Suprema de Justicia", "Betancourt House", "Tribunal Superior de Cuentas (TSC)", "Escuela Judicial de Honduras", "Centro De Justicia Civil", "Tribunal de Sentencias Tegucigalpa", "JUZGADO DE NIÑEZ", "JUZGADO DE EJECUCION PENAL", "Sala de lo Social");

        for ($i=0; $i < 14; $i++) { 
            $juzgado = new Juzgado();

            $juzgado->setNombre($juzgadosArray[$i]);
            $juzgado->setDireccion($this->faker->address());

            $manager->persist($juzgado);
        }

        $manager->flush();
    }

    public function loadTpProcedimiento(ObjectManager $manager){
        $tpProcedimientoArray = array("El Procedimiento Ordinario", "El Procedimiento Abreviado", "Los Procesos Ejecución", "El Procedimiento Monitorio");

        for ($i=0; $i < 4 ; $i++) { 
            $tpProced = new TpProcedimiento();
            $tpProced->setNombre($tpProcedimientoArray[$i]);
            $tpProced->setDescripcion($this->faker->sentence());

            $manager->persist($tpProced);
        }

        $manager->flush();
    }  

    public function loadTpActuacion(ObjectManager $manager){
        $tpActuacionArray = array("Actuación", "Vencimiento", "Gasto", "Correo enviado", "Documentación", "Control de horario", "señalamiento", "llamada entrante", "llamada saliente");

        for ($i=0; $i < count($tpActuacionArray) ; $i++) { 
            $tpActuacion = new TpActuacion();
            $tpActuacion->setNombre($tpActuacionArray[$i]);
            $tpActuacion->setDescripcion($this->faker->sentence());

            $manager->persist($tpActuacion);
        }

        $manager->flush();
    }
}
