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
        // si ça ne marche pas, je pense qu'on doit ajouter un login dans UserController
        // Quand un user est crée alors il est automatiquement loguer
        // car user_id est null

        // Un utilisateur ne peut avoir qu'une seule todolist
        // - Une todolist peut avoir un nom et une description
        // - Un item doit obligatoirement avoir un nom (unique pour une même liste), et peut avoir une description
        // (jusqu'à 1000 caractères)
        // - Un délai de 30 min doit être respecté entre l'ajout de 2 items dans une même todolist
        // - Un item ne peut être ajouté à une liste que si le propriétaire de la liste est valide
        // // return !empty($this->name)
        // // && strlen($this->name) <= 255
        // // && !empty($this->user)
        // // && is_null($this->description) || strlen($this->description) <= 255;
        // // TEST si user est login
        // // TEST si user a déjà une todolist
        // // TEST la todolist maximum 10 items
        // // TEST si ajout item on todolist is success
        // // TEST ajout item trop récent; 30mmn between another add

        }
        if ($form->isValid()) {
            $todolist->setName("My todolist");
            $todolist->setDescription("My description...");
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
            return new JsonResponse("SUCCESS : Here's your todolist : Name : " . $todolist->getName() . " Description : " . $todolist->getDescription(), 201);
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
    /**
     * @Route("/todolist/additem", name="todolist/additem", methods={"POST"})
     */
    public function addItem(Request $request, EntityManagerInterface $em, UserRepository $repository): Response
    {
        $email = $request->headers->get('php-auth-user');
        $password = $request->headers->get('php-auth-pw');

        // Verifier que l'utilisateur existe et que le password renseigne est correct
        //retrieveUser(email, password);  => User.find(email=email, password=password)
        $user = $repository->findOneBy([
            'email' => $email,
            'password' => $password
        ]);

        // si c'est null ou empty -> retourne erreur, utilisateur n'existe pas ou mal authentifie
        if (empty($user)) {
            return new JsonResponse("ERROR : user doesn't exist or wrong authentication...", 500);
        }
        // recuperer la todolist associe a l'utilisateur, si elle n'existe pas retourner erreur
        // verifier que la todolist est valide
        // si c'est pas valide, retourne erreur
        if (!$user->getTodolist()->isValid()) {
            return new JsonResponse("ERROR : user's toDoList is invalid...", 500);
        }
        // si c'est valdie, tu crees l'item et tu verifie que l'item est ok
        $item = new Item('', '', Carbon::now());
        $formItem = $this->createForm(ItemType::class, $item);
        $formItem->submit($request->request->all());
        if (!$formItem->isValid()) {
            return new JsonResponse("ERROR : item is wrong !", 500);
        }
        $em->persist($item);
        $em->flush();
        try {
            $user->getTodolist()->canAddItem($item);
        } catch (Exceptionn $e) {
            return new JsonResponse("ERROR : item is wrong !", 500);
            throw $e;
        }

        // retrieveUsersTodolist(user_name)
        // $item = new Item();
        // $formItem = $this->createForm(ItemType::class, $item);
        // $formItem->submit($request->request->all());
        // if ($formItem->isValid()) {

        //     if(!$item->isValid()){
        //         return new JsonResponse("ERROR : something wrong !",500);
        //     }

        return new JsonResponse("Success...", 200);
    }

    // function retrieveUsersTodolist(todolist_id) {
    // recupere user depuis header Authorization
}
