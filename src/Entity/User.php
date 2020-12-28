<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use PHPUnit\Runner\Exception;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
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
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="integer")
     */
    private $birthday;

    /**
     * @ORM\OneToOne(targetEntity=Todolist::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $todolist;

    public function __construct( string $firstname, string $lastname, int $birthday, string $email, string $password)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->birthday = $birthday;
        $this->email = $email;
        $this->password = $password;
    }

    public function isValid(){
        if (empty($this->firstname))
            return new Exception('Le prénom est vide');
        if (empty($this->lastname))
            return new Exception('Le nom est vide');
        if ($this->birthday < 13)
            return new Exception('L\'utilisateur doit  être agé de 13 ans au minimum');
        if (empty($this->email))
            return new Exception('L\'email est vide');
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
            return new Exception('Format d\'email non valide');
        if (empty($this->password))
            return new Exception('Le mot de passe est vide');
        if (strlen($this->password) < 8 || strlen($this->password) > 40)
            return new Exception('Le mot de passe doit faire entre 8 et 40 caractères');
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
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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
     * @return Carbon
     */
    public function getBirthday(): Carbon
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
        return $this->todolist;
    }

    public function setTodolist(?Todolist $todolist): self
    {
        $this->todolist = $todolist;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $todolist ? null : $this;
        if ($todolist->getUser() !== $newUser) {
            $todolist->setUser($newUser);
        }

        return $this;
    }
}
