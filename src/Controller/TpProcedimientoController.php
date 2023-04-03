<?php

namespace App\Controller;

use App\Entity\TpProcedimiento;
use App\Form\TpProcedimientoFormType;
use App\Repository\TpProcedimientoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tp-procedimiento', name: 'tp_procedimiento_', requirements: ['_locale' => 'en|es|fr'])]
class TpProcedimientoController extends AbstractController
{
    public function __construct(
        private TpProcedimientoRepository $tpProcedimientoRepo,
        private EntityManagerInterface $emi
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $tpProcedimientos = $this->tpProcedimientoRepo->findAll();
        // dd($juzgados);

        return $this->render('tp_procedimiento/index.html.twig', compact('tpProcedimientos'));
    }

    // funcion para crear un tipo procedimiento y redirigir hacia index
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $tpProcedimiento = new TpProcedimiento();

        $form = $this->createForm(TpProcedimientoFormType::class, $tpProcedimiento);

        $form->handleRequest($request);


        // $procuradorNA = $form->get('numAbogado')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            

            $tpProcedimiento = $form->getData();


            $this->emi->persist($tpProcedimiento);
            $this->emi->flush();

            return $this->redirectToRoute('tp_procedimiento_index');
        }
        return $this->render('tp_procedimiento/create.html.twig', [
            'form_tp_proced' => $form->createView()
        ]);
    }

     // funcion para editar un tipo procedimoiento por medio de un formulario
     #[Route('/edit/{id}', name: 'edit')]
     public function edit($id, Request $request): Response
     {
         $tpProcedimiento = $this->tpProcedimientoRepo->find($id);
 
         $form_tpProced = $this->createForm(TpActuacionFormType::class, $tpProcedimiento);
 
         $form_tpProced->handleRequest($request);
 
         // $data = $form_procurador->getData();
 
         // $content = json_decode($request->getContent());
         // dd($content);
 
             if ($form_tpProced->isSubmitted() && $form_tpProced->isValid()) {
 
                 $this->emi->flush();
 
                 return $this->redirectToRoute('tp_procedimiento_index');
             }
         
         return $this->render('tp_procedimiento/edit.html.twig', [
             'tpProcedimiento' => $tpProcedimiento,
             'form_tp_proced' => $form_tpProced->createView()
         ]);
     }

    // funcion ajaxGet para responder al request de Datatable tipo procedimiento
    #[Route('/ajax_get', methods: ['GET'], name: 'ajax_get')]
    public function ajaxGet(): JsonResponse
    {
        $tpProcedAjax = $this->tpProcedimientoRepo->findAll();
        //   dd($juzgadoAjax);
        $jsonData = array();
        $idx = 0;

        foreach ($tpProcedAjax as $tpProced) {
            $temp = array(
                "id" => $tpProced->getId(),
                "nombre" => $tpProced->getNombre(),
                "descripcion" => $tpProced->getDescripcion(),
            );
            $jsonData[$idx++] = $temp;
        }

        return new JsonResponse($jsonData);
    }

    // funcion ajax para eliminar un Tipo procediemtno desde la tabla
    #[Route('/ajax_delete/{id}', name: 'delete', methods:['GET','DELETE'])]
    public function delete($id)
    {
        $rep_tpProced = $this->emi->getRepository(TpProcedimiento::class);
        $tpProced_del = $rep_tpProced->find($id);

        $this->emi->remove($tpProced_del);
        $this->emi->flush();
        
        $response = new Response();
        $response->send();
    }
}
