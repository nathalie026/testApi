<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class UserController extends AbstractController
{
    /**
     * @Route("/createuserok", methods={"POST"})
     */
    public function createuserok(Request $request, EntityManagerInterface $entityManagerInterface)
    {
        $user = new User("salome", "test", 13, "test@live.fr", "azertyuiop");

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response("User create OK", 201);
        }

        return new JsonResponse($form->getErrors());
    }
}
