<?php

namespace App\Controller;

use App\Repository\ProcuradorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Procurador;
use App\Form\ProcuradorFormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/procurador', name: 'procurador_', requirements: ['_locale' => 'es|en|fr'])]
class ProcuradorController extends AbstractController
{
    public function __construct(
        private ProcuradorRepository $procuradorRepo,
        private EntityManagerInterface $emi
    )
    {    }
    
    #[Route('', name: 'index')]
    public function index(): Response
    {
        $procuradores = $this->procuradorRepo->findAll();

        return $this->render('procurador/index.html.twig', compact('procuradores'));
    }

      // funcion para crear un Cliente y redirigir hacia index
      #[Route('/create', name: 'create')]
      public function create(Request $request): Response
      {
          $procurador = new Procurador();
  
          $form = $this->createForm(ProcuradorFormType::class, $procurador);
  
          $form->handleRequest($request);
  
          
          $procuradorNA = $form->get('numAbogado')->getData();
  
          if ($form->isSubmitted() && $form->isValid()) {
            //   $clientenuevo = new Procurador();
              $newpersona = $form->get('persona')->getViewData();
  
              $this->emi->persist($newpersona);
              $this->emi->flush();
            //   $lasID = $newpersona->getID();
  
              $procurador->setPersona($newpersona);
              $procurador->setNumAbogado($procuradorNA);
  
  
              $this->emi->persist($procurador);
              $this->emi->flush();
  
              return $this->redirectToRoute('procurador_index');
          }
          return $this->render('procurador/create.html.twig', [
              'form_procurador' => $form->createView()
          ]);
      }

      // funcion para editar un juez por medio de un formulario
    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id, Request $request): Response
    {
        $procurador = $this->procuradorRepo->find($id);

        $form_procu = $this->createForm(ProcuradorFormType::class, $procurador);

        $form_procu->handleRequest($request);

        // $data = $form_procurador->getData();

        // $content = json_decode($request->getContent());
        // dd($content);

            if ($form_procu->isSubmitted() && $form_procu->isValid()) {

                // dd($data);
                $editpersona = $form_procu->get('persona')->getViewData();

                $procurador->setNumAbogado($form_procu->get('numAbogado')->getData());
                $procurador->setPersona($editpersona);

                $this->emi->flush();

                return $this->redirectToRoute('procurador_index');
            }
        
        return $this->render('procurador/edit.html.twig', [
            'procurador' => $procurador,
            'form_procu' => $form_procu->createView()
        ]);
    }

      // funcion ajaxGet para responder al request de Datatable procuradores
    #[Route('/ajax_get', methods: ['GET'], name: 'ajax_get')]
    public function ajaxGet(SerializerInterface $serializer): Response
    {
        $procuradorAjax = $this->procuradorRepo->findAll();
        // dd($juecesAjax);
        $jsonData = array();
        $idx = 0;

        foreach ($procuradorAjax as $procurador) {
            $temp = array(
                "id" => $procurador->getId(),
                "numProf" => $procurador->getNumAbogado(),
                "nombre" => $procurador->getPersona()->getNombre(),
                "dni" => $procurador->getPersona()->getDni(),
                "email" => $procurador->getPersona()->getEmail(),
                "direccion" => $procurador->getPersona()->getDireccion(),
                "telefono" => $procurador->getPersona()->getTelefono()
            );
            $jsonData[$idx++] = $temp;
        }

        return new JsonResponse($jsonData);
    }

    // funcion ajax para eliminar un procurador desde la tabla
    #[Route('/ajax_delete/{id}', name: 'delete', methods:['GET','DELETE'])]
    public function delete($id)
    {
        $rep_procur = $this->emi->getRepository(Procurador::class);
        $procur_del = $rep_procur->find($id);

        $this->emi->remove($procur_del);
        $this->emi->flush();
        
        $response = new Response();
        $response->send();
    }
}
