<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 * @method string getUserIdentifier()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username_canonical;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email_canonical;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = 1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsernameCanonical()
    {
        return $this->username_canonical;
    }

    /**
     * @param mixed $username_canonical
     * @return User
     */
    public function setUsernameCanonical($username_canonical)
    {
        $this->username_canonical = $username_canonical;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailCanonical()
    {
        return $this->email_canonical;
    }

    /**
     * @param mixed $email_canonical
     * @return User
     */
    public function setEmailCanonical($email_canonical)
    {
        $this->email_canonical = $email_canonical;
        return $this;
    }

    /**
     * @return int
     */
    public function getEnabled(): int
    {
        return $this->enabled;
    }

    /**
     * @param int $enabled
     * @return User
     */
    public function setEnabled(int $enabled): User
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles(array $roles): User
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getSalt(): ?string
    {
        //not used here
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __call(string $name, array $arguments)
    {
    }

}
