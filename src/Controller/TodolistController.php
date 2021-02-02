<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TodolistType;
use App\Entity\Todolist;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;


class TodolistController extends AbstractController
{
    /**
     * @Route("/todolist", name="todolist", methods={"POST"})
     */
    public function createTodolist(Request $request, EntityManagerInterface $em): Response
    {
        // si ça ne marche pas, je pense qu'on doit ajouter un login dans UserController
        // Quand un user est crée alors il est automatiquement loguer
        // car user_id est null
        $todolist= new Todolist();
        $form = $this->createForm(TodolistType::class,$todolist);
        $form->submit($request->request->all());
        if(!$this->getUser())
        {
            return new JsonResponse("ERROR : user don't exist...", 500);
        }
        if(!$this->getUser()->getTodolist())
        {
            return new JsonResponse("ERROR :user already have todolist...", 500);
        }
        if ($form->isValid()) {
            $todolist->setUser($this->getUser());
            $em->persist($todolist);
            $em->flush();

            return new JsonResponse("SUCCESS : todolist created", 201);

        }

        return new JsonResponse("ERROR : Oops, something is wrong...", 500);
    }


    // créer une fonction pour l'ajout d'item
}
