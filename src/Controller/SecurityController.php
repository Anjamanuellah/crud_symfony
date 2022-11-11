<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration", methods="{GET}", "{POST}")
     */
    public function registration(Request $request, EntityManagerInterface $entityManager , UserPasswordHasherInterface $userPasswordHasher) {
        $utilisateur = new Utilisateur();

        $form = $this->createForm(RegistrationType::class, $utilisateur);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword($userPasswordHasher->hashPassword($utilisateur, $utilisateur->getPassword()));

            $entityManager->persist($utilisateur);
            $entityManager->flush();
        }
        return $this->render('security/registration.html.twig', [
            'form' =>$form->createView()
        ]);
    }
}
