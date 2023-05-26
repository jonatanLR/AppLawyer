<?php

namespace App\Controller;

use App\Entity\Role;
use App\Form\RoleFormType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $roles = $this->roleRepo->findAll();

        return $this->render('role/index.html.twig', compact('roles'));
    }

    // funcion para crear un rol y redirigir hacia index
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $role = new Role();

        $form = $this->createForm(RoleFormType::class, $role);

        $form->handleRequest($request);


        // $procuradorNA = $form->get('numAbogado')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            

            $role = $form->getData();


            $this->emi->persist($role);
            $this->emi->flush();

            return $this->redirectToRoute('role_index');
        }
        return $this->render('role/create.html.twig', [
            'form_role' => $form->createView()
        ]);
    }

    // funcion para editar un rol por medio de un formulario
    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id, Request $request): Response
    {
        $role = $this->roleRepo->find($id);

        $form_role = $this->createForm(RoleFormType::class, $role);

        $form_role->handleRequest($request);

        // $data = $form_procurador->getData();

        // $content = json_decode($request->getContent());
        // dd($content);

            if ($form_role->isSubmitted() && $form_role->isValid()) {

                $this->emi->flush();

                return $this->redirectToRoute('role_index');
            }
        
        return $this->render('role/edit.html.twig', [
            'role' => $role,
            'form_role' => $form_role->createView()
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
                "status" => $role->isStatus() ? 'activo' : 'desactivo'
            );
            $jsonData[$idx++] = $temp;
        }

        return new JsonResponse($jsonData);
    }
}
