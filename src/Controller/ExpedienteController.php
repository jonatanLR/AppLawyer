<?php

namespace App\Controller;

use App\Entity\Expediente;
use App\Form\ExpedienteFormType;
use App\Repository\ExpedienteRepository;
use App\Services\Generador;
use App\Services\GeneradorIDExped;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

// #[AsController]
#[Route('/expediente', name: 'expediente_', requirements: ['_locale' => 'es|en|fr'])]
class ExpedienteController extends AbstractController
{
    public function __construct(
        private ExpedienteRepository $expedRepo,
        private EntityManagerInterface $emi,
        private GeneradorIDExped $generador
        )
    {
        
    }
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $expedientes = $this->expedRepo->findBy(['estado' => ['Activo', 'Pendiente', 'Cerrado']], ['id' => 'DESC']);

        return $this->render('expediente/index.html.twig', compact('expedientes'));
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $newExped = new Expediente();

        $newIdExpediente = $this->generador->nextId();

        $form = $this->createForm(ExpedienteFormType::class, $newExped, [
            'newExpedID' => $newIdExpediente
        ]);

        // $reqData = $request->request->get('expediente_form')['juezes'];
        // $reqData = $request->request->all()['expediente_form']['contrarios'];
        // dd($reqData);
        // dd($request->request->getp);

        $form->handleRequest($request);
        // dd($form->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            // Obtener los datos para setearlos
            $newIdExp = $form->get('id')->getData();
            $arrCollecJueces = $form->get('juezes')->getData();
            $arrCollecContrarios = $form->get('contrarios')->getData();
            $arrCollecClientes = $form->get('clientes')->getData();
            $arrCollecUsers = $form->get('users')->getData();
            $procurador = $form->get('procurador')->getData();
            $juzgado = $form->get('juzgado')->getData();
            $tpProcedimiento = $form->get('tpProcedimiento')->getData();

            $newExped->setId($newIdExp);

            // add jueces
            foreach ($arrCollecJueces as $juez) {
                $newExped->addJueze($juez);
            }

            // add contrarios
            foreach ($arrCollecContrarios as $contrario) {
                $newExped->addContrario($contrario);
            }

            // add clientes
            foreach ($arrCollecClientes as $cliente) {
                $newExped->addCliente($cliente);
            }

            // add users
            foreach ($arrCollecUsers as $user) {
                $newExped->addUser($user);
            }

            $newExped->setProcurador($procurador);
            $newExped->setJuzgado($juzgado);
            $newExped->setTpProcedimiento($tpProcedimiento);

            $this->emi->persist($newExped);
            $this->emi->flush();

            return $this->redirectToRoute('expediente_index');
        }

        return $this->render('expediente/create.html.twig', [
            'form_exped' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id, Request $request): Response
    { 
        $expediente = $this->expedRepo->find($id);
        // dd($user->getRole());
        // dd($expediente);
        $form_exped = $this->createForm(ExpedienteFormType::class, $expediente, [
            'isEdit' => true
        ]);

        $form_exped->handleRequest($request);
        // dd($form_exped->get('estado')->getData());
        if ($form_exped->isSubmitted() && $form_exped->isValid()) {
            // Obtener los datos para setearlos
            // $editIdExp = $form_exped->get('id')->getData();
            $arrCollecJueces = $form_exped->get('juezes')->getData();
            $arrCollecContrarios = $form_exped->get('contrarios')->getData();
            $arrCollecClientes = $form_exped->get('clientes')->getData();
            $arrCollecUsers = $form_exped->get('users')->getData();
            $procurador = $form_exped->get('procurador')->getData();
            $juzgado = $form_exped->get('juzgado')->getData();
            $tpProcedimiento = $form_exped->get('tpProcedimiento')->getData();

            // add jueces
            foreach ($arrCollecJueces as $juez) {
                $expediente->addJueze($juez);
            }

            // add contrarios
            foreach ($arrCollecContrarios as $contrario) {
                $expediente->addContrario($contrario);
            }

            // add clientes
            foreach ($arrCollecClientes as $cliente) {
                $expediente->addCliente($cliente);
            }

            // add users
            foreach ($arrCollecUsers as $user) {
                $expediente->addUser($user);
            }

            $expediente->setProcurador($procurador);
            $expediente->setJuzgado($juzgado);
            $expediente->setTpProcedimiento($tpProcedimiento);

           
            $this->emi->flush();

            return $this->redirectToRoute('expediente_index');
        } // fin submitted y is_valid

        return $this->render('expediente/edit.html.twig', [
            'expediente' => $expediente,
            'form_exped' => $form_exped->createView(),
            'editExped' => true
        ]);

    }

    // // funcion ajax para eliminar un usuario desde la tabla
    // #[Route('/ajax_delete/{id}', name: 'delete', methods: ['GET', 'DELETE'])]
    // public function delete($id)
    // {
    //     $rep_exped = $this->emi->getRepository(Expediente::class);
    //     $exped_del = $rep_exped->find($id);
    //     // dd($id);
    //     // $fotoPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/photos/" . $user_del->getFoto();
    //     // if ($this->fsObject->exists($fotoPath)) {
    //     //     $this->fsObject->remove($fotoPath);
    //     // }

    //     $this->emi->remove($exped_del);
    //     $this->emi->flush();

    //     $response = new Response();
    //     $response->send();
    // }

    // funcion ajax para eliminar EXPEDIENTE DE FORMA LOGICA desde la tabla
    #[Route('/ajax_delete/{id}', name: 'delete', methods: ['GET', 'DELETE'])]
    public function delete($id)
    {
        $rep_exped = $this->emi->getRepository(Expediente::class);
        $exped_del = $rep_exped->find($id);
        // $exped_del = $this->expedRepo->find($id);
        // $exped_del->setEstado('Eliminado');
        // dd($exped_del);
        $exped_del->setEstado('Eliminado');
        // dd($exped_del);
        // $this->emi->remove($exped_del);
        // $this->emi->persist($exped_del);
        $this->emi->flush();

        $response = new Response();
        $response->send();
    }

    // funcion ajaxGet to get expediente instance by id
    #[Route('/ajax_get/{id}', methods: ['GET'], name: 'ajax_get')]
    public function ajaxGet($id)
    {
        // $clienteAjax = $this->clienterepo->findAll();
        $expediente = $this->expedRepo->findBy($id);
        // $jsonData = array();
        // $idx = 0;

        // foreach ($clienteAjax as $cliente) {
        //     $temp = array(
        //         "id" => $cliente->getId(),
        //         "tipo" => $cliente->getTipoCC()->getNombre(),
        //         "nombre" => $cliente->getPersona()->getNombre(),
        //         "dni" => $cliente->getPersona()->getDni(),
        //         "email" => $cliente->getPersona()->getEmail(),
        //         "direccion" => $cliente->getPersona()->getDireccion(),
        //         "telefono" => $cliente->getPersona()->getTelefono()
        //     );
        //     $jsonData[$idx++] = $temp;
        // }

        return $expediente;
    }
}
