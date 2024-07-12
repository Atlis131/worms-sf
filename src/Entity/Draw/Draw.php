<?php

namespace App\Entity\Draw;

use App\Entity\User\User;
use App\Repository\Draw\DrawRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrawRepository::class)]
class Draw
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user;

    #[ORM\Column(type: 'boolean',options: ['default' => false])]
    private ?bool $includeTools = false;

    #[ORM\Column(type: 'boolean',options: ['default' => false])]
    private ?bool $includeSentryGuns = false;

    #[ORM\Column(type: 'boolean',options: ['default' => false])]
    private ?bool $includeSuperWeapons = false;

    #[ORM\Column(type: 'boolean',options: ['default' => false])]
    private ?bool $includeOpenMapWeapons = false;

    #[ORM\Column(type: 'boolean',options: ['default' => false])]
    private ?bool $randomWeaponsCount = false;

    #[ORM\Column(type: 'boolean',options: ['default' => false])]
    private ?bool $randomWeaponsDelay = false;

    #[ORM\Column(type: 'datetime')]
    private ?DateTime $createdAt;

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

    public function getIncludeTools(): ?bool
    {
        return $this->includeTools;
    }

    public function setIncludeTools(?bool $includeTools): Draw
    {
        $this->includeTools = $includeTools;
        return $this;
    }

    public function getIncludeSentryGuns(): ?bool
    {
        return $this->includeSentryGuns;
    }

    public function setIncludeSentryGuns(?bool $includeSentryGuns): Draw
    {
        $this->includeSentryGuns = $includeSentryGuns;
        return $this;
    }

    public function getIncludeSuperWeapons(): ?bool
    {
        return $this->includeSuperWeapons;
    }

    public function setIncludeSuperWeapons(?bool $includeSuperWeapons): Draw
    {
        $this->includeSuperWeapons = $includeSuperWeapons;
        return $this;
    }

    public function getIncludeOpenMapWeapons(): ?bool
    {
        return $this->includeOpenMapWeapons;
    }

    public function setIncludeOpenMapWeapons(?bool $includeOpenMapWeapons): Draw
    {
        $this->includeOpenMapWeapons = $includeOpenMapWeapons;
        return $this;
    }

    public function getRandomWeaponsCount(): ?bool
    {
        return $this->randomWeaponsCount;
    }

    public function setRandomWeaponsCount(?bool $randomWeaponsCount): Draw
    {
        $this->randomWeaponsCount = $randomWeaponsCount;
        return $this;
    }

    public function getRandomWeaponsDelay(): ?bool
    {
        return $this->randomWeaponsDelay;
    }

    public function setRandomWeaponsDelay(?bool $randomWeaponsDelay): Draw
    {
        $this->randomWeaponsDelay = $randomWeaponsDelay;
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

}
