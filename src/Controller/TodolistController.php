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
        $todolist= new Todolist();
        $form = $this->createForm(TodolistType::class,$todolist);
        $form->submit($request->request->all());
        if(!$this->getUser())
        {
            return new JsonResponse("ERROR : You aren't logged in...", 500);
        }
        if($this->getUser()->getTodolist())
        {
            return new JsonResponse("ERROR : User already has a todolist...", 500);
        }
        if ($form->isValid()) {
            if(!$todolist->isValid()){
                return new JsonResponse("ERROR : something wrong !", 500);
            }
            $todolist->setUser($this->getUser());
            $em->persist($todolist);
            $em->flush();

            return new JsonResponse("SUCCESS : Todolist created", 201);

        }

        return new JsonResponse("ERROR : Oops, something went wrong...", 500);
    }

    /**
     * @Route("/todolist/show", name="todolist_show", methods={"GET"})
     */
    public function showTodolist(Request $request)
    {
        if(!$this->getUser())
        {
            return new JsonResponse("ERROR : You aren't logged in...", 500);
        }
        if(!$this->getUser()->getTodolist())
        {
            return new JsonResponse("ERROR : You have to create a todolist...", 500);
        }
        if($this->getUser()->getTodolist())
        {
            $todolist = $this->getUser()->getTodolist();
            return new JsonResponse("SUCCESS : Here's your todolist : Name : " . $todolist->getName() . " Description : " . $todolist->getDescription(), 200);
        }

        return new JsonResponse("ERROR : Oops, something went wrong...", 500);
    }

    /**
     * @Route("/todolistInte", name="todolist_inte", methods={"POST"})
     */
    public function createTodolistInte(Request $request, EntityManagerInterface $em): Response
    {
        $todolist = new Todolist();
        $form = $this->createForm(TodolistType::class,$todolist);
        $form->submit($request->request->all());

        if ($form->isValid()) {

            if(!$todolist->isValid()){
                return new JsonResponse("ERROR : something wrong !", 500);
            }

            $em->persist($todolist);
            $em->flush();

            return new JsonResponse("SUCCESS : todolist created", 201);

        }

        return new JsonResponse("ERROR : Oops, something is wrong...", 500);
    }


    // créer une fonction pour l'ajout d'item
}
