<?php

namespace App\Entity\Weapon;

use App\Repository\Weapon\WeaponRepository;
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

    #[ORM\Column(type: 'boolean',options: ['default' => false])]
    private ?bool $isSuperWeapon = false;

    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 1])]
    private ?int $minDraw = 1;

    // 10 => Infinity
    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 10])]
    private ?int $maxDraw = 10;

    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 0])]
    private ?int $minDelay = 0;

    #[ORM\Column(type: 'smallint', nullable: false, options: ['default' => 9])]
    private ?int $maxDelay = 9;

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

    public function getIsSuperWeapon(): ?bool
    {
        return $this->isSuperWeapon;
    }

    public function setIsSuperWeapon(?bool $isSuperWeapon): Weapon
    {
        $this->isSuperWeapon = $isSuperWeapon;
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

    public function getMinDraw(): ?int
    {
        return $this->minDraw;
    }

    public function setMinDraw(?int $minDraw): Weapon
    {
        $this->minDraw = $minDraw;
        return $this;
    }

    public function getMaxDraw(): ?int
    {
        return $this->maxDraw;
    }

    public function setMaxDraw(?int $maxDraw): Weapon
    {
        $this->maxDraw = $maxDraw;
        return $this;
    }

    public function getMinDelay(): ?int
    {
        return $this->minDelay;
    }

    public function setMinDelay(?int $minDelay): Weapon
    {
        $this->minDelay = $minDelay;
        return $this;
    }

    public function getMaxDelay(): ?int
    {
        return $this->maxDelay;
    }

    public function setMaxDelay(?int $maxDelay): Weapon
    {
        $this->maxDelay = $maxDelay;
        return $this;
    }
}
