<?php

namespace App\Controller;

use App\Entity\Contrario;
use App\Entity\TipoCC;
use App\Form\ContrarioFormType;
use App\Repository\ContrarioRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contrario', requirements: ['_locale' => 'en|es|fr'], name: "contrario_")]

class ContrarioController extends AbstractController
{
    private $repocontrario;
    private $emi;

    public function __construct(ContrarioRepository $repocontrario, EntityManagerInterface $emi)
    {
        $this->repocontrario = $repocontrario;
        $this->emi = $emi;
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $contrarios = $this->repocontrario->findAll();
        $contrarioss = $this->emi->getRepository(Contrario::class)->findAll();
        // dd($contrarioss);
        return $this->render('contrario/index.html.twig', compact('contrarios'));
    }

    // funcion para crear un Contrario y redirigir hacia index
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $contrario = new Contrario();

        $form = $this->createForm(ContrarioFormType::class, $contrario);

        $form->handleRequest($request);

        // $data = $form->get('persona')->getViewData();
        // $data = $form->get('num_profesion')->getData();
        $contrariotp = $form->get('tipo')->getData();

        // dd($data);
        if ($form->isSubmitted() && $form->isValid()) {
            // $persona = new Persona();
            $contrarionuevo = new Contrario();
            $newpersona = $form->get('persona')->getViewData();

            $this->emi->persist($newpersona);
            $this->emi->flush();
            $lasID = $newpersona->getID();

            $contrarionuevo->setPersona($newpersona);
            // $contrarionuevo->setTipo($contrariotp);
            $contrarionuevo->setTipoCC($contrariotp);


            $this->emi->persist($contrarionuevo);
            $this->emi->flush();

            return $this->redirectToRoute('contrario_index');
        }
        return $this->render('contrario/create.html.twig', [
            'formContrario' => $form->createView()
        ]);
    }

    // funcion para editar un contrario por medio de un formulario
    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id, Request $request): Response
    {
        $contrario = $this->repocontrario->find($id);

        $form_contra = $this->createForm(ContrarioFormType::class, $contrario, [
            'isEdit' => true
        ]);

        $form_contra->handleRequest($request);

        $data = $form_contra->getData();

        // $content = json_decode($request->getContent());
        // dd($data);

            if ($form_contra->isSubmitted() && $form_contra->isValid()) {

                // dd($data);
                $editpersona = $form_contra->get('persona')->getViewData();

                $contrario->setTipoCC($form_contra->get('tipo')->getData());
                $contrario->setPersona($editpersona);

                $this->emi->flush();

                return $this->redirectToRoute('contrario_index');
            }
        
        return $this->render('contrario/edit.html.twig', [
            'contrario' => $contrario,
            'form_contra' => $form_contra->createView()
        ]);
    }

    // funcion ajax para eliminar un contrario desde la tabla
    #[Route('/ajax_delete/{id}', name: 'delete', methods:['GET','DELETE'])]
    public function delete($id)
    {
        $rep_contra = $this->emi->getRepository(Contrario::class);
        $contrario_del = $rep_contra->find($id);

        $this->emi->remove($contrario_del);
        $this->emi->flush();
        
        $response = new Response();
        $response->send();
    }

    // funcion ajaxGet para responder al request de Datatable contrarios
    #[Route('/ajax_get', methods: ['GET'], name: 'ajax_get')]
    public function ajaxGet(): Response
    {
        $contrarioAjax = $this->repocontrario->findAll();
        // dd($juecesAjax);
        $jsonData = array();
        $idx = 0;

        foreach ($contrarioAjax as $contrario) {
            $temp = array(
                "id" => $contrario->getId(),
                "tipo" => $contrario->getTipoCC()->getNombre(),
                "nombre" => $contrario->getPersona()->getNombre(),
                "dni" => $contrario->getPersona()->getDni(),
                "email" => $contrario->getPersona()->getEmail(),
                "direccion" => $contrario->getPersona()->getDireccion(),
                "telefono" => $contrario->getPersona()->getTelefono()
            );
            $jsonData[$idx++] = $temp;
        }

        return new JsonResponse($jsonData);
    }

}
