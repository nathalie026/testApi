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
        $isValid = $user->isValid();
        if ($form->isValid() || $isValid) {
//            mettre les conditions pour créer un user
            if(!empty($em->getRepository(User::class)->find($user->getFirstname()))) {
                return new JsonResponse("ERROR : Firstname empty", 500);
            }
            if(!empty($em->getRepository(User::class)->find($user->getLastname()))) {
                return new JsonResponse("ERROR : Lastname empty", 500);
            }
            if(!empty($em->getRepository(User::class)->find($user->getBirthday()))) {
                return new JsonResponse("ERROR : Birthday empty", 500);
            }
            if(!empty($em->getRepository(User::class)->find($user->getEmail()))) {
                return new JsonResponse("ERROR : Email empty", 500);
            }
            if(!empty($em->getRepository(User::class)->find($user->getPassword()))) {
                return new JsonResponse("ERROR : Password empty", 500);
            }



            $em->persist($user);
            $em->flush();

            return new JsonResponse("SUCCESS : user created", 201);

        }

        return new JsonResponse("ERROR : Oops, something is wrong...", 500);
    }
}
