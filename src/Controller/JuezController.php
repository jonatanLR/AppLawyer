<?php

namespace App\Controller;

use App\Entity\Juez;
use App\Entity\Persona;
use App\Form\JuezFormType;
use App\Repository\JuezRepository;
use App\Repository\PersonaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use function PHPSTORM_META\type;

#[Route('/juez', requirements: ['_locale' => 'en|es|fr'], name: "juez_")]
class JuezController extends AbstractController
{
    private $juezRepository;
    private $emi;
    private $personaRepository;

    public function __construct(JuezRepository $juezRepository, EntityManagerInterface $emi, PersonaRepository $personaRepository)
    {
        $this->juezRepository = $juezRepository;
        $this->emi = $emi;
        $this->personaRepository = $personaRepository;
    }

    // funcion index para mostrar el inicio de la lista de jueces en una tabla
    #[Route('', methods: ['GET'], name: 'index')]
    public function index(): Response
    {
        $jueces = $this->juezRepository->findAll();
        // dd($jueces);

        return $this->render('juez/index.html.twig', compact('jueces'));
    }

    // funcion para crear un juez y redirigir hacia index
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $juez = new Juez();

        $form = $this->createForm(JuezFormType::class);

        $form->handleRequest($request);

        // $data = $form->get('persona')->getViewData();
        // $data = $form->get('num_profesion')->getData();
        $juezNP = $form->get('num_profesion')->getData();

        // dd($data);
        if ($form->isSubmitted() && $form->isValid()) {
            // $persona = new Persona();
            $jueznuevo = new Juez();
            $newpersona = $form->get('persona')->getViewData();

            $this->emi->persist($newpersona);
            $this->emi->flush();
            $lasID = $newpersona->getID();

            $jueznuevo->setPersona($newpersona);
            $jueznuevo->setNumProfesion($juezNP);

            $this->emi->persist($jueznuevo);
            $this->emi->flush();

            return $this->redirectToRoute('juez_index');
        }
        return $this->render('juez/create.html.twig', [
            'formJuez' => $form->createView()
        ]);
    }

    // funcion para editar un juez por medio de un formulario
    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id, Request $request): Response
    {
        $juez = $this->juezRepository->find($id);

        $form_je = $this->createForm(JuezFormType::class, $juez);

        $form_je->handleRequest($request);

        $data = $form_je->getData();

        // $content = json_decode($request->getContent());
        // dd($content);

            if ($form_je->isSubmitted() && $form_je->isValid()) {

                // dd($data);
                $editpersona = $form_je->get('persona')->getViewData();

                $juez->setNumProfesion($form_je->get('num_profesion')->getData());
                $juez->setPersona($editpersona);

                $this->emi->flush();

                return $this->redirectToRoute('juez_index');
            }
        
        return $this->render('juez/edit.html.twig', [
            'juez' => $juez,
            'form_je' => $form_je->createView()
        ]);
    }

    // funcion ajax para eliminar un juez desde la tabla
    #[Route('/ajax_delete/{id}', name: 'delete', methods:['GET','DELETE'])]
    public function delete($id)
    {
        $rep_juez = $this->emi->getRepository(Juez::class);
        $juez_del = $rep_juez->find($id);

        $this->emi->remove($juez_del);
        $this->emi->flush();
        
        $response = new Response();
        $response->send();
    }

    // funcion ajaxGet para responder al request de Datatable jueces
    #[Route('/ajax_get', methods: ['GET'], name: 'ajax_get')]
    public function ajaxGet(SerializerInterface $serializer): Response
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

        return new JsonResponse($jsonData);
    }
}
