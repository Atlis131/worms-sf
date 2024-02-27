<?php

namespace App\Entity\Weapon;

use App\Repository\WeaponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeaponRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Weapon
{
    const WEAPON_TYPE_REGULAR = 0;
    const WEAPON_TYPE_CRAFTED = 1;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $imageName;

    #[ORM\Column(type: 'smallint', nullable: false)]
    private int $type;

    #[ORM\Column(type: 'boolean',options: ['default' => false])]
    private ?bool $isTool = false;

    #[ORM\Column(type: 'boolean',options: ['default' => false])]
    private ?bool $isOpenMapWeapon = false;

    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 1])]
    private ?int $min = 1;

    // 10 => Infinity
    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 10])]
    private ?int $max = 10;

    #[ORM\ManyToOne(targetEntity: Weapon::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Weapon $baseVersion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * @param string|null $imageName
     * @return Weapon
     */
    public function setImageName(?string $imageName): Weapon
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsTool(): ?bool
    {
        return $this->isTool;
    }

    /**
     * @param bool|null $isTool
     * @return Weapon
     */
    public function setIsTool(?bool $isTool): Weapon
    {
        $this->isTool = $isTool;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsOpenMapWeapon(): ?bool
    {
        return $this->isOpenMapWeapon;
    }

    /**
     * @param bool|null $isOpenMapWeapon
     * @return Weapon
     */
    public function setIsOpenMapWeapon(?bool $isOpenMapWeapon): Weapon
    {
        $this->isOpenMapWeapon = $isOpenMapWeapon;

        return $this;
    }

    public function getBaseVersion(): ?Weapon
    {
        return $this->baseVersion;
    }

    public function setBaseVersion(?Weapon $baseVersion): Weapon
    {
        $this->baseVersion = $baseVersion;

        return $this;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function setMin(?int $min): Weapon
    {
        $this->min = $min;
        return $this;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(?int $max): Weapon
    {
        $this->max = $max;
        return $this;
    }

}
