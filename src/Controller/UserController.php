<?php

namespace App\Controller;

use App\Entity\User;

use App\Repository\UserRepository;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends AbstractController
{

    /**
     * @Route("/create", name="app_user_create" , methods={"GET", "POST"})
     */
    public function create(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
                     ->add('firstname')
                     ->add('lastname')
                     ->add('adress')
                     ->add('telephone')
                     ->add('save', SubmitType::class, [
                        'label'=>'enregistrer'
                     ])

                     ->getForm();

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
     * @Route("/show", name="app_user_show")
     */
    public function show(UserRepository $userRepository): Response
    {
        return $this->render('user/show.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
