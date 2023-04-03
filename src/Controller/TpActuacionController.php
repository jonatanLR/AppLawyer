<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TpActuacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\TpActuacion;
use App\Form\TpActuacionFormType;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/tp-actuacion', name: 'tp_actuacion_', requirements: ['_locale' => 'es|en|fr'])]
class TpActuacionController extends AbstractController
{
    public function __construct(
        private TpActuacionRepository $tpActuacionRepo,
        private EntityManagerInterface $emi
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $tpActuaciones = $this->tpActuacionRepo->findAll();
        // dd($juzgados);

        return $this->render('tp-actuacion/index.html.twig', compact('tpActuaciones'));
    }

    // funcion para crear un tipo actuacion y redirigir hacia index
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $tpActuacion = new TpActuacion();

        $form = $this->createForm(TpActuacionFormType::class, $tpActuacion);

        $form->handleRequest($request);


        // $procuradorNA = $form->get('numAbogado')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            

            $tpActuacion = $form->getData();


            $this->emi->persist($tpActuacion);
            $this->emi->flush();

            return $this->redirectToRoute('tp_actuacion_index');
        }
        return $this->render('tp-actuacion/create.html.twig', [
            'form_tp_actu' => $form->createView()
        ]);
    }

     // funcion para editar un tipo actuacion por medio de un formulario
     #[Route('/edit/{id}', name: 'edit')]
     public function edit($id, Request $request): Response
     {
         $tpActuacion = $this->tpActuacionRepo->find($id);
 
         $form_tpActu = $this->createForm(TpActuacionFormType::class, $tpActuacion,[
            'isEdit' => true
         ]);
 
         $form_tpActu->handleRequest($request);
 
         // $data = $form_procurador->getData();
 
         // $content = json_decode($request->getContent());
         // dd($content);
 
             if ($form_tpActu->isSubmitted() && $form_tpActu->isValid()) {
 
                 $this->emi->flush();
 
                 return $this->redirectToRoute('tp_actuacion_index');
             }
         
         return $this->render('tp-actuacion/edit.html.twig', [
             'tpActuacion' => $tpActuacion,
             'form_tp_actu' => $form_tpActu->createView()
         ]);
     }

    // funcion ajaxGet para responder al request de Datatable tipo actuacion
    #[Route('/ajax_get', methods: ['GET'], name: 'ajax_get')]
    public function ajaxGet(): JsonResponse
    {
        $tpActuacionAjax = $this->tpActuacionRepo->findAll();
        //   dd($juzgadoAjax);
        $jsonData = array();
        $idx = 0;

        foreach ($tpActuacionAjax as $tpActua) {
            $temp = array(
                "id" => $tpActua->getId(),
                "nombre" => $tpActua->getNombre(),
                "descripcion" => $tpActua->getDescripcion(),
            );
            $jsonData[$idx++] = $temp;
        }

        return new JsonResponse($jsonData);
    }

    // funcion ajax para eliminar un Tipo actuacion desde la tabla
    #[Route('/ajax_delete/{id}', name: 'delete', methods:['GET','DELETE'])]
    public function delete($id)
    {
        $rep_tpActu = $this->emi->getRepository(TpActuacion::class);
        $tpActu_del = $rep_tpActu->find($id);

        $this->emi->remove($tpActu_del);
        $this->emi->flush();
        
        $response = new Response();
        $response->send();
    }
}
