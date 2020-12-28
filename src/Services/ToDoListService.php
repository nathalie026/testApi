<?php

namespace App\Services;

use App\Repository\TodolistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PHPUnit\Runner\Exception;
use Carbon\Carbon;

/**
 * @ORM\Services(repositoryClass=TodolistRepository::class)
 */
class ToDoListService
{

}


