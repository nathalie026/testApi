<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Carbon\Carbon;

use Doctrine\ORM\Mapping as ORM;

use PHPUnit\Runner\Exception;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $birthday;

    /**
     * @ORM\OneToOne(targetEntity=Todolist::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $Todolist;


    //    public function __construct( string $firstname, string $lastname, int $birthday, string $email, string $password)
    public function __construct(string $firstname = null, string $lastname = null, int $birthday = null, string $email = null, string $password = null)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->birthday = $birthday;
        $this->email = $email;
        $this->password = $password;
    }


    public function isValid()
    {
        if (empty($this->firstname))
            throw new Exception('Le prénom est vide');
        if (empty($this->lastname))
            throw new Exception('Le nom est vide');
        if ($this->birthday < 13)
            throw new Exception('L\'utilisateur doit  être agé de 13 ans au minimum');
        if (empty($this->email))
            throw new Exception('L\'email est vide');
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
            throw new Exception('Format d\'email non valide');
        if (empty($this->password))
            throw new Exception('Le mot de passe est vide');
        if (strlen($this->password) < 8 || strlen($this->password) > 40)
            throw new Exception('Le mot de passe doit faire entre 8 et 40 caractères');
        return true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }





    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }



    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }
    /**
     * @return 
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param Carbon $birthday
     */
    public function setBirthday(int $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getTodolist(): ?Todolist
    {
        return $this->Todolist;
    }

    public function setTodolist(Todolist $Todolist): self
    {
        $this->Todolist = $Todolist;
        if ($Todolist->getUser() !== $this) {
            $Todolist->setUser($this);
        }

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
