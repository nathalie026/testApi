<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Todolist::class, inversedBy="items")
     */
    private $Todolist;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    public $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTodolist(): ?Todolist
    {
        return $this->Todolist;
    }

    public function setTodolist(?Todolist $Todolist): self
    {
        $this->Todolist = $Todolist;

        return $this;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function __construct($name, $content, $createdAt)
    {
        $this->name = $name;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }

    public function isValid(): bool
    {
        return !empty($this->name)
            && !empty($this->content)
            && !empty($this->createdAt)
            && strlen($this->content) <= 1000;
    }
}
