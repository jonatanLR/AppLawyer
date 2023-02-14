<?php

namespace App\Controller;

use App\Repository\JuezRepository;
use App\Repository\PersonaRepository;
use PharIo\Manifest\Requirement;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Route as RoutingRoute;

use function PHPSTORM_META\type;

#[Route('/juez', requirements:['_locale' => 'en|es|fr'], name:"juez_")]
class JuezController extends AbstractController
{
    private $juezRepository;
    private $personaRepository;

    public function __construct(JuezRepository $juezRepository, PersonaRepository $personaRepository)
    {
        $this->juezRepository = $juezRepository;
        $this->personaRepository = $personaRepository;
        
    }

    #[Route('',methods:['GET'], name: 'index')]
    public function index(): Response
    {
        $jueces = $this->juezRepository->findAll();
        // dd($jueces);

        return $this->render('juez/index.html.twig', compact('jueces'));
    }
}
    