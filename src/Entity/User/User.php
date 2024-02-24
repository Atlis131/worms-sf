<?php

namespace App\Entity\User;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $username_canonical;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email_canonical;

    #[ORM\Column(type: 'boolean')]
    private bool $enabled = true;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[Assert\Length(min: 8)]
    #[Assert\Regex(pattern: "/[a-z]/", message: "Your password must contain at least one lowercase letter")]
    #[Assert\Regex(pattern: "/[A-Z]/", message: "Your password must contain at least one uppercase letter")]
    #[Assert\Regex(pattern: "/\d/", message: "Your password must contain at least one digit")]
    #[Assert\Regex(pattern: "/[^a-zA-Z0-9]/", message: "Your password must contain at least one special character")]
    #[Assert\Regex(pattern: "/ /", message: "Your password cannot contain spaces", match: false)]
    #[Assert\NotCompromisedPassword]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $name;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $registrationDate;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $emailVerificationDate;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $resetPasswordToken;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $resetPasswordTokenTime;

    #[ORM\Column(type: 'array')]
    private array $roles = [];

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsernameCanonical(): string
    {
        return $this->username_canonical;
    }

    /**
     * @param string $username_canonical
     * @return User
     */
    public function setUsernameCanonical(string $username_canonical): static
    {
        $this->username_canonical = $username_canonical;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailCanonical(): string
    {
        return $this->email_canonical;
    }

    /**
     * @param string $email_canonical
     * @return User
     */
    public function setEmailCanonical(string $email_canonical): static
    {
        $this->email_canonical = $email_canonical;
        return $this;
    }

    /**
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return User
     */
    public function setEnabled(bool $enabled): User
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     * @return User
     */
    public function setPlainPassword(?string $plainPassword): User
    {
        $this->plainPassword = $plainPassword;
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
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): static
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getEmailVerificationDate(): ?DateTime
    {
        return $this->emailVerificationDate;
    }

    /**
     * @param DateTime|null $emailVerificationDate
     * @return User
     */
    public function setEmailVerificationDate(?DateTime $emailVerificationDate): static
    {
        $this->emailVerificationDate = $emailVerificationDate;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getRegistrationDate(): ?DateTime
    {
        return $this->registrationDate;
    }

    /**
     * @param DateTime|null $registrationDate
     * @return User
     */
    public function setRegistrationDate(?DateTime $registrationDate): static
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getResetPasswordTokenTime(): ?DateTime
    {
        return $this->resetPasswordTokenTime;
    }

    /**
     * @param DateTime|null $resetPasswordTokenTime
     * @return User
     */
    public function setResetPasswordTokenTime(?DateTime $resetPasswordTokenTime): static
    {
        $this->resetPasswordTokenTime = $resetPasswordTokenTime;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    /**
     * @param string|null $resetPasswordToken
     * @return User
     */
    public function setResetPasswordToken(?string $resetPasswordToken): static
    {
        $this->resetPasswordToken = $resetPasswordToken;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }

    public function __call(string $name, array $arguments)
    {
    }
}
