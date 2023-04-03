<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/role', name: 'role_', requirements: ['_locale' => 'en|es|fr'])]
class RoleController extends AbstractController
{
    public function __construct(
        private RoleRepository $roleRepo,
        private EntityManagerInterface $emi
    )
    {
        
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('role/index.html.twig', [
            'controller_name' => 'RoleController',
        ]);
    }

    // funcion ajaxGet para responder al request de Datatable role
    #[Route('/ajax_get', methods: ['GET'], name: 'ajax_get')]
    public function ajaxGet(): JsonResponse
    {
        $roleAjax = $this->roleRepo->findAll();
        //   dd($juzgadoAjax);
        $jsonData = array();
        $idx = 0;

        foreach ($roleAjax as $role) {
            $temp = array(
                "id" => $role->getId(),
                "name" => $role->getName(),
                'role_name' => $role->getRoleName(),
                "status" => $role->isStatus()
            );
            $jsonData[$idx++] = $temp;
        }

        return new JsonResponse($jsonData);
    }
}
