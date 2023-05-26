<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

#[Route('/user', name: 'user_', requirements: ['_locale' => 'es|en|fr'])]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepo,
        private EntityManagerInterface $emi,
        private Filesystem $fsObject
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $users = $this->userRepo->findAll();

        return $this->render('user/index.html.twig', compact('users'));
    }

    // funcion para crear un Cliente y redirigir hacia index
    #[Route('/create', name: 'create')]
    public function create(Request $request, SluggerInterface $slugger, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        $userNA = $form->get('numAbogado')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            // get roles from the fomr
            $roles = $form->get('roles')->getData();

            // get email of persona to put user email
            $dataemail = $form->get('persona')->getViewData()->getEmail();

            // get data persona to insert in entity persona
            $newpersona = $form->get('persona')->getViewData();

            // get password field to hasher
            $password = $form->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $password
            );

            // gestionar el archivo foto
            $file = $form->get('foto')->getData();
            // dd($file);
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('photoDir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups Hay un problema con tu archivo');
                }

                $user->setFoto($newFilename);
            }


            $this->emi->persist($newpersona);
            $this->emi->flush();
            //   $lasID = $newpersona->getID();
            $user->setEmail($dataemail);
            $user->setPersona($newpersona);
            $user->setNumAbogado($userNA);
            // $user->setRoles($roles);
            foreach ($roles as $role) {
                $user->addRole($role);
            }

            $user->setPassword($hashedPassword);

            $this->emi->persist($user);
            $this->emi->flush();

            return $this->redirectToRoute('user_index');
        }
        return $this->render('user/create.html.twig', [
            'form_user' => $form->createView()
        ]);
    }

    // funcion para editar un juez por medio de un formulario
    #[Route('/edit/{id}', name: 'edit')]
    public function edit($id, Request $request, SluggerInterface $slugger): Response
    {
        $user = $this->userRepo->find($id);
        // dd($user->getRole());

        $form_user = $this->createForm(UserFormType::class, $user, [
            'isEdit' => true
        ]);

        $form_user->handleRequest($request);
        // $data = $form_user->get('roles')->getData();

        if ($form_user->isSubmitted() && $form_user->isValid()) {
            $roles = $form_user->get('roles')->getData();

            // get email of persona to put user email
            $dataemail = $form_user->get('persona')->getViewData()->getEmail();
            $editpersona = $form_user->get('persona')->getViewData();

            // gestionar el archivo foto
            $file = $form_user->get('foto')->getData();
            $fotoActual = $form_user->get('fotoActual')->getData();
            //  dd($file);
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('photoDir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups Hay un problema con tu archivo');
                }

                $user->setFoto($newFilename);

                $fotoPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/photos/" . $fotoActual;
                if ($this->fsObject->exists($fotoPath)) {
                    $this->fsObject->remove($fotoPath);
                }
            }

            foreach ($roles as $role) {
                $user->addRole($role);
            }

            $user->setEmail($dataemail);
            $user->setNumAbogado($form_user->get('numAbogado')->getData());
            $user->setPersona($editpersona);

            $this->emi->flush();

            return $this->redirectToRoute('user_index');
        } // fin submitted y is_valid

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form_user' => $form_user->createView(),
            'editUser' => true
        ]);
    }

    // funcion ajaxGet para responder al request de Datatable procuradores
    #[Route('/ajax_get', methods: ['GET'], name: 'ajax_get')]
    public function ajaxGet(): Response
    {
        $userAjax = $this->userRepo->findAll();
        // dd($juecesAjax);
        $jsonData = array();
        $idx = 0;

        foreach ($userAjax as $user) {
            $temp = array(
                "id" => $user->getId(),
                "numProf" => $user->getNumAbogado(),
                "tipo" => $user->getTipo(),
                "foto" => $user->getFoto(),
                "nombre" => $user->getPersona()->getNombre(),
                "dni" => $user->getPersona()->getDni(),
                "email" => $user->getPersona()->getEmail(),
                "direccion" => $user->getPersona()->getDireccion(),
                "telefono" => $user->getPersona()->getTelefono()
            );
            $jsonData[$idx++] = $temp;
        }

        return new JsonResponse($jsonData);
    }

    // funcion ajax para eliminar un usuario desde la tabla
    #[Route('/ajax_delete/{id}', name: 'delete', methods: ['GET', 'DELETE'])]
    public function delete($id)
    {
        $rep_user = $this->emi->getRepository(User::class);
        $user_del = $rep_user->find($id);

        $fotoPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/photos/" . $user_del->getFoto();
        if ($this->fsObject->exists($fotoPath)) {
            $this->fsObject->remove($fotoPath);
        }

        $this->emi->remove($user_del);
        $this->emi->flush();

        $response = new Response();
        $response->send();
    }
}
