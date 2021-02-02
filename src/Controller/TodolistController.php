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
     * @Route("/todolist", name="todolist")
     */
    public function index(): Response
    {
        return $this->render('todolist/index.html.twig', [
            'controller_name' => 'TodolistController',
        ]);
    }
}
