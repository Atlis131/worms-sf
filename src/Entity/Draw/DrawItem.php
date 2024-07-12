<?php

namespace App\Entity\Draw;

use App\Entity\Weapon\Weapon;
use App\Repository\Draw\DrawItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrawItemRepository::class)]
class DrawItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Draw::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Draw $draw;

    #[ORM\ManyToOne(targetEntity: Weapon::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Weapon $weapon;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $count;

    #[ORM\Column(type: 'smallint', nullable: true)]
    private ?int $delay;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDraw(): Draw
    {
        return $this->draw;
    }

    public function setDraw(Draw $draw): DrawItem
    {
        $this->draw = $draw;
        return $this;
    }

    public function getWeapon(): Weapon
    {
        return $this->weapon;
    }

    public function setWeapon(Weapon $weapon): DrawItem
    {
        $this->weapon = $weapon;
        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(?int $count): DrawItem
    {
        $this->count = $count;
        return $this;
    }

    public function getDelay(): ?int
    {
        return $this->delay;
    }

    public function setDelay(?int $delay): DrawItem
    {
        $this->delay = $delay;
        return $this;
    }

}
