<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{


    /**
     * @Route("/createuser", name="createuser", methods={"POST"})
     */
    public function createUser(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class,$user);
        $form->submit($request->request->all());

        if ($form->isValid()) {

            if(!$user->isValid()){
                return new JsonResponse("ERROR : something wrong !",500);
            }

            $em->persist($user);
            $em->flush();

            return new JsonResponse("SUCCESS : user created", 201);

        }

        return new JsonResponse("ERROR : Oops, something is wrong...", 500);
    }

    // créer méthode login
}
