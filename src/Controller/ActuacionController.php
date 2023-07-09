<?php

namespace App\Controller;

use App\Entity\Actuacion;
use App\Entity\Expediente;
use App\Entity\TpActuacion;
use App\Form\ActuacionFormType;
use App\Repository\ExpedienteRepository;
use App\Repository\TpActuacionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActuacionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $emi,
        private ExpedienteRepository $expedRepo,
        private TpActuacionRepository $tpActuacionRepo
        )
    {
        
    }
    #[Route('/actuacion/create', name: 'actuacion_create')]
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        // $expedRepo = $this->emi->getRepository(Expediente::class);
        // $tpActuacionRepo = $this->emi->getRepository(TpActuacion::class);
        $findExped = $data['id_exped'];
        $findTpAct = $data['tipoAct'];
        $expediente = $this->expedRepo->find($findExped);
        $tipoActuacion = $this->tpActuacionRepo->find($findTpAct);

        $nombre = $data['nombre'];
        $fecha = $data['fecha'];
        $descripcion = $data['descripcion'];
        $direccion = $data['direccion'];

        $newActuacion = new Actuacion();

        $newActuacion->setNombre($nombre);
        $newActuacion->setFechaAlta(new DateTime($fecha));
        $newActuacion->setDescripcion($descripcion);
        $newActuacion->setDireccion($direccion);
        $newActuacion->setExpediente($expediente);
        $newActuacion->setTpActuacion($tipoActuacion);        

        $this->emi->persist($newActuacion);
        $this->emi->flush();
        
        $response = new Response();
        $response->setContent("Success");
        $response->setStatusCode(200);
        $response->send();
    }
}
