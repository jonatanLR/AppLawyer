<?php

namespace App\Controller;

use App\Repository\JuezRepository;
use App\Repository\PersonaRepository;
use PharIo\Manifest\Requirement;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Route as RoutingRoute;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

use function PHPSTORM_META\type;

#[Route('/juez', requirements: ['_locale' => 'en|es|fr'], name: "juez_")]
class JuezController extends AbstractController
{
    private $juezRepository;
    private $personaRepository;

    public function __construct(JuezRepository $juezRepository, PersonaRepository $personaRepository)
    {
        $this->juezRepository = $juezRepository;
        $this->personaRepository = $personaRepository;
    }

    #[Route('', methods: ['GET'], name: 'index')]
    public function index(): Response
    {
        $jueces = $this->juezRepository->findAll();
        // dd($jueces);

        return $this->render('juez/index.html.twig', compact('jueces'));
    }

    #[Route('/ajax_get', methods: ['GET'], name: 'ajax_get')]
    public function ajaxGet(SerializerInterface $serializer):Response
    {
        $juecesAjax = $this->juezRepository->findAll();
        // dd($juecesAjax);
        $jsonData = array();
        $idx = 0;

        foreach ($juecesAjax as $juez) {
            $temp = array(
                "id" => $juez->getId(),
                "numProf" => $juez->getNumProfesion(),
                "nombre" => $juez->getPersona()->getNombre(),
                "dni" => $juez->getPersona()->getDni(),
                "email" => $juez->getPersona()->getEmail(),
                "direccion" => $juez->getPersona()->getDireccion(),
                "telefono" => $juez->getPersona()->getTelefono()
            );
            $jsonData[$idx++] = $temp;
        }
        // $jsonData = $serializer->serialize($juecesAjax, 'json');
        // return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
            // $jsonencode = json_encode($juecesAjax);
            // $jsonreplace = str_replace(["[","]"],["{","}"],$jsonencode);
            // $jsondecode = json_decode($jsonreplace);
            // dd($jsonreplace);

        return new JsonResponse($jsonData);
    }
}
