<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

use Exception;

class UserController extends AbstractController
{


    /**
     * @Route("/createuser", name="createuser", methods={"POST"})
     */
    public function createUser(Request $request, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            if (!$user->isValid()) {
                return new JsonResponse("ERROR : something wrong !", 500);
            }

            $repository = $em->getRepository(User::class);
            try {
                $em->persist($user);
                $em->flush();
            } catch (Exception $e) {
                return new JsonResponse("Error : Cannot save user " . $e->getMessage(), 500);
            }

            return new JsonResponse("SUCCESS : user created", 201);
        }

        return new JsonResponse("ERROR : Oops, something is wrong...", 500);
    }

    /**
     * @Route("/logintest", name="logintest", methods={"POST"})
     */
    public function logintest(Request $request)
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse("ERROR : invalid credentials !", 500);
        }

        return new JsonResponse("SUCCESS :" . $user->getUsername() . " is logged", 200);
    }

    /**
     * @Route("/checkLogin", name="checkLogin", methods={"GET"})
     */
    public function checkLogin(Request $request)
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse("ERROR : You aren't logged in !", 500);
        }

        return new JsonResponse("SUCCESS :" . $user->getUsername() . " is logged", 200);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile()
    {
        return $this->render('user/index.html.twig');
    }
}
