<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Form\ClienteFormType;
use App\Repository\ClienteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cliente', requirements: ['_locale' => 'en|es|fr'], name: "cliente_")]
class ClienteController extends AbstractController
{
    public function __construct(
        private ClienteRepository $clienterepo, 
        private EntityManagerInterface $emi)
    {
        
    }
    #[Route('', name: 'index')]
    public function index(): Response
    {
        $clientes = $this->clienterepo->findAll();
        // dd($clientes);
        return $this->render('cliente/index.html.twig', compact('clientes'));
    }

        // funcion para crear un Cliente y redirigir hacia index
        #[Route('/create', name: 'create')]
        public function create(Request $request): Response
        {
            $cliente = new Cliente();
    
            $form = $this->createForm(ClienteFormType::class, $cliente);
    
            $form->handleRequest($request);
    
            
            $clientetp = $form->get('tipo')->getData();
    
            if ($form->isSubmitted() && $form->isValid()) {
                $clientenuevo = new Cliente();
                $newpersona = $form->get('persona')->getViewData();
    
                $this->emi->persist($newpersona);
                $this->emi->flush();
                $lasID = $newpersona->getID();
    
                $clientenuevo->setPersona($newpersona);
                $clientenuevo->setTipoCC($clientetp);
    
    
                $this->emi->persist($clientenuevo);
                $this->emi->flush();
    
                return $this->redirectToRoute('cliente_index');
            }
            return $this->render('cliente/create.html.twig', [
                'form_cliente' => $form->createView()
            ]);
        }
    
        // funcion para editar un cliente por medio de un formulario
        #[Route('/edit/{id}', name: 'edit')]
        public function edit($id, Request $request): Response
        {
            $cliente = $this->clienterepo->find($id);
    
            $form_cliente = $this->createForm(ClienteFormType::class, $cliente, [
                'isEdit' => true
            ]);
    
            $form_cliente->handleRequest($request);
    
            $data = $form_cliente->getData();
    
                if ($form_cliente->isSubmitted() && $form_cliente->isValid()) {
    
                    $editpersona = $form_cliente->get('persona')->getViewData();
    
                    $cliente->setTipoCC($form_cliente->get('tipo')->getData());
                    $cliente->setPersona($editpersona);
    
                    $this->emi->flush();
    
                    return $this->redirectToRoute('cliente_index');
                }
            
            return $this->render('cliente/edit.html.twig', [
                'cliente' => $cliente,
                'form_cliente' => $form_cliente->createView()
            ]);
        }
    
        // funcion ajax para eliminar un cliente desde la tabla
        #[Route('/ajax_delete/{id}', name: 'delete', methods:['GET','DELETE'])]
        public function delete($id)
        {
            $rep_cliente = $this->emi->getRepository(Cliente::class);
            $cliente_del = $rep_cliente->find($id);
    
            $this->emi->remove($cliente_del);
            $this->emi->flush();
            
            $response = new Response();
            $response->send();
        }
    
        // funcion ajaxGet para responder al request de Datatable clientes
        #[Route('/ajax_get', methods: ['GET'], name: 'ajax_get')]
        public function ajaxGet(): Response
        {
            $clienteAjax = $this->clienterepo->findAll();
            $jsonData = array();
            $idx = 0;
    
            foreach ($clienteAjax as $cliente) {
                $temp = array(
                    "id" => $cliente->getId(),
                    "tipo" => $cliente->getTipoCC()->getNombre(),
                    "nombre" => $cliente->getPersona()->getNombre(),
                    "dni" => $cliente->getPersona()->getDni(),
                    "email" => $cliente->getPersona()->getEmail(),
                    "direccion" => $cliente->getPersona()->getDireccion(),
                    "telefono" => $cliente->getPersona()->getTelefono()
                );
                $jsonData[$idx++] = $temp;
            }
    
            return new JsonResponse($jsonData);
        }
}
