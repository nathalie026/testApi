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
    public function isValid()
    {
        return !empty($this->name)
        && strlen($this->name) <= 255
        && (is_null($this->description) || strlen($this->description) <= 255);
    }

   /**
     * @param Item $item
     * @return Item
     * @throws Exceptions
     */

     public function canAddItem(Item $item) : Item {
         if (is_null($item) || !$item->isValid()) {
             throw new Exception("Ton item est null ou invalide");
         }

         if (is_null($this->user) || !$this->user->isValid() ) {
             throw new Exception("User est null ou invalide");
         }

         if ($this->actualItemsCount() >= 10) {
            throw new Exception("La ToDoList comporte beaucoup trop d items, maximum 10");
        }

        $lastItem = $this->getLastItem();
        if (!is_null($this->getLastItem()) && Carbon::now()->subMinutes(30)->isBefore($lastItem->created_at)) {
            throw new Exception("Last item est trop récent, 30mn entre la création de 2 items");
        }

        return $item;
     }

     public function getLastItem(): ?Item {
         return $this->items->first();
     }

     public function actualItemsCount() {
         return sizeof($this->items()->get());
     }
}


