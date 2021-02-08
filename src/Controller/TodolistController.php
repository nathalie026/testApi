<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\TodolistType;
use App\Entity\Todolist;
use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Exception;


// si ça ne marche pas, je pense qu'on doit ajouter un login dans UserController
// Quand un user est crée alors il est automatiquement loguer
// car user_id est null
class TodolistController extends AbstractController
{
    /**
     * @Route("/todolist", name="todolist", methods={"POST"})
     */
    public function createTodolist(Request $request, EntityManagerInterface $em): Response
    {
        $todolist = new Todolist();
        $form = $this->createForm(TodolistType::class, $todolist);
        $form->submit($request->request->all());
        if (!$this->getUser()) {
            return new JsonResponse("ERROR : You aren't logged in...", 500);
        }
        if ($this->getUser()->getTodolist()) {
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
     * @Route("/todolistInte", name="todolist_inte", methods={"POST"})
     */
    public function createTodolistInte(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return new JsonResponse("ERROR : You aren't logged in...", 500);
        }
        if ($this->getUser()->getTodolist()) {
            return new JsonResponse("ERROR : User already has a todolist...", 500);
        }
        $todolist = new Todolist();
        $form = $this->createForm(TodolistType::class, $todolist);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            if (!$todolist->isValid()) {
                return new JsonResponse("ERROR : something wrong !", 500);
            }

            try {
                $todolist->setUser($this->getUser());
                $em->persist($todolist);
                $em->flush();
            } catch (Exception $e) {
                return new JsonResponse("ERROR while trying to save todolist " . $e->getMessage(), 500);
            }

            return new JsonResponse("SUCCESS : todolist created", 201);
        }

        return new JsonResponse("ERROR : Oops, something is wrong...", 500);
    }


    /**
     * @Route("/todolist/show", name="todolist_show", methods={"GET"})
     */
    public function showTodolist(Request $request)
    {
        if (!$this->getUser()) {
            return new JsonResponse("ERROR : You aren't logged in...", 500);
        }
        if (!$this->getUser()->getTodolist()) {
            return new JsonResponse("ERROR : You have to create a todolist...", 500);
        }
        if ($this->getUser()->getTodolist()) {
            $todolist = $this->getUser()->getTodolist();
            return new JsonResponse("SUCCESS : Here's your todolist : Name : " . $todolist->getName() . " Description : " . $todolist->getDescription(), 200);
        }

        return new JsonResponse("ERROR : Oops, something went wrong...", 500);
    }


    // créer une fonction pour l'ajout d'item
    /**
     * @Route("/todolist/additem", name="todolist/additem", methods={"POST"})
     */
    public function addItem(Request $request, EntityManagerInterface $em, UserRepository $repository): Response
    {
        if (!$this->getUser()) {
            return new JsonResponse("ERROR : You aren't logged in...", 500);
        }
        if (!$this->getUser()->getTodolist()) {
            return new JsonResponse("ERROR : You have to create a todolist...", 500);
        }
        $todolist = $this->getUser()->getTodoList();
        // verifier que la todolist est valide
        // si c'est pas valide, retourne erreur
        if (!$todolist->isValid()) {
            return new JsonResponse("ERROR : user's toDoList is invalid...", 500);
        }

        // si c'est valdie, tu crees l'item et tu verifie que l'item est ok
        $item = new Item("", "", Carbon::now());

        $formItem = $this->createForm(ItemType::class, $item);

        $formItem->submit($request->request->all());

        if (!$formItem->isValid()) {
            return new JsonResponse("ERROR : item is invalid !", 500);
            $em->persist($item);
        }

        $em->persist($item);

        try {
            if ($todolist->canAddItem($item)) {
                $item->setTodolist($todolist);
                $em->persist($todolist);
                $em->flush();
                return new JsonResponse("SUCESS : item " . $item->getName() . " " . $item->getContent() . ' added', 201);
            }
        } catch (Exception $e) {
            return new JsonResponse("ERROR : " . $e->getMessage(), 500);
        }
    }
}
