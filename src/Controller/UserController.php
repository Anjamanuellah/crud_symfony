<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    /**
     * @Route("/create", name="app_user_create" , methods={"GET", "POST"})
     */
    public function create(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_show',[], Response::HTTP_SEE_OTHER);
        }  

        return $this->render('user/create.html.twig', [
            'formUser' => $form->createView(),
            'user'=> $user,
            'form' => $form,

        ]);
    }

    /**
     * @Route("/show", name="app_user_show", methods={"GET"})
     */
    public function show(UserRepository $userRepository): Response
    {
        return $this->render('user/show.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_user_delete", methods={"POST", "GET"})
     */
    public function delete(Request $request, UserRepository $userRepository, $id): Response
    {
        $user = $userRepository->find($id);
        if($this->isCsrfTokenValid('delete'.$user->getId(), 
        $request->request->get('_token'))) {
            $userRepository->remove($user, true);
            return $this->redirectToRoute('app_user_show');
        }
    }

    /**
     * @Route("/edit/{id}", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, UserRepository $userRepository, $id): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
