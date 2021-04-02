<?php

namespace App\Entity;

use App\Repository\TodolistRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use App\Service\EmailService;


/**
 * @ORM\Entity(repositoryClass=TodolistRepository::class)
 */
class Todolist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="Todolist", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="Todolist", , orphanRemoval=true)
     */
    private $item;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    public function __construct()
    {
        $this->item = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     * @return Collection|Item[]
     */
    public function getItem(): Collection
    {
        return $this->item;
    }
    public function removeItem(Item $item): self
    {
        if ($this->item->removeElement($item)) {
            // set the owning side to null (unless already changed)
            if ($item->getTodoList() === $this) {
                $item->setTodoList(null);
            }
        }

        return $this;
    }

    public function isValid(): bool
    {
        return !empty($this->name)
            && strlen($this->name) <= 255
            && !empty($this->user)
            && is_null($this->description) || strlen($this->description) <= 255;
    }

    /**
     * @param Item $item
     * @return Item
     * @throws Exception
     */

    public function canAddItem(Item $item) {
        if (is_null($item) || !$item->isValid()) {
            throw new Exception("Ton item est null ou invalide");
        }

        /* if (is_null($this->user) || !$this->user->isValid() ) {
            throw new Exception("User est null ou invalide");
        } */


        if ($this->getSizeTodoItemsCount() >= 10) {
            throw new Exception("La ToDoList comporte beaucoup trop d items, maximum 10");
        }

        $lastItem = $this->getLastItem();
        if (!is_null($this->getLastItem()) && Carbon::now()->subMinutes(30)->isBefore($lastItem->createdAt)) {
            throw new Exception("Last item est trop récent, 30mn entre la création de 2 items");
        }
        $this->AlertEightItems();
        return $item;
    }

    public function AlertEightItems()
    {
        if($this->getSizeTodoItemsCount() == 8)
        {
            $this->sendEmailToUser();
            return true;
        }
    }

    public function getLastItem(): ?Item {
        return $this->getItem()->last();
    }

    public function getSizeTodoItemsCount() {
        return sizeof($this->getItem());
    }

    protected function sendEmailToUser()
    {
        $emailService = new EmailService();
        $mailer = new \Swift_Mailer();
        $emailService->sendMail('Il vous reste 2 items',$this->user->getEmail(), $mailer);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
