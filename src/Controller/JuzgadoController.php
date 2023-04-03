<?php

namespace App\Controller;

use App\Repository\JuzgadoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Juzgado;
use App\Form\JuzgadoFormType;

#[Route('/juzgado', name: 'juzgado_', requirements: ['_locale' => 'es|en|fr'])]
class JuzgadoController extends AbstractController
{
    public function __construct(
        private JuzgadoRepository $juzgadoRepo,
        private EntityManagerInterface $emi
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $juzgados = $this->juzgadoRepo->findAll();
        // dd($juzgados);

        return $this->render('juzgado/index.html.twig', compact('juzgados'));
    }

    // funcion para crear un Cliente y redirigir hacia index
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $juzgado = new Juzgado();

        $form = $this->createForm(JuzgadoFormType::class, $juzgado);

        $form->handleRequest($request);


        // $procuradorNA = $form->get('numAbogado')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            

            $juzgado = $form->getData();


            $this->emi->persist($juzgado);
            $this->emi->flush();

            return $this->redirectToRoute('juzgado_index');
        }
        return $this->render('juzgado/create.html.twig', [
            'form_juzgado' => $form->createView()
        ]);
    }

     // funcion para editar un juez por medio de un formulario
     #[Route('/edit/{id}', name: 'edit')]
     public function edit($id, Request $request): Response
     {
         $juzgado = $this->juzgadoRepo->find($id);
 
         $form_juzgado = $this->createForm(JuzgadoFormType::class, $juzgado);
 
         $form_juzgado->handleRequest($request);
 
         // $data = $form_procurador->getData();
 
         // $content = json_decode($request->getContent());
         // dd($content);
 
             if ($form_juzgado->isSubmitted() && $form_juzgado->isValid()) {
 
                 $this->emi->flush();
 
                 return $this->redirectToRoute('juzgado_index');
             }
         
         return $this->render('juzgado/edit.html.twig', [
             'juzgado' => $juzgado,
             'form_juzgado' => $form_juzgado->createView()
         ]);
     }

    // funcion ajaxGet para responder al request de Datatable juzgados
    #[Route('/ajax_get', methods: ['GET'], name: 'ajax_get')]
    public function ajaxGet(): Response
    {
        $juzgadoAjax = $this->juzgadoRepo->findAll();
        //   dd($juzgadoAjax);
        $jsonData = array();
        $idx = 0;

        foreach ($juzgadoAjax as $juzgado) {
            $temp = array(
                "id" => $juzgado->getId(),
                "nombre" => $juzgado->getNombre(),
                "direccion" => $juzgado->getDireccion(),
            );
            $jsonData[$idx++] = $temp;
        }

        return new JsonResponse($jsonData);
    }

    // funcion ajax para eliminar un Juzgado desde la tabla
    #[Route('/ajax_delete/{id}', name: 'delete', methods:['GET','DELETE'])]
    public function delete($id)
    {
        $rep_juzgado = $this->emi->getRepository(Juzgado::class);
        $juzgado_del = $rep_juzgado->find($id);

        $this->emi->remove($juzgado_del);
        $this->emi->flush();
        
        $response = new Response();
        $response->send();
    }

}
