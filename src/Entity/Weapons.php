<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WeaponsRepository")
 * @ORM\Table(name="weapons")
 */
class Weapons
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $imageName = null;

    /**
     * @ORM\Column(type="smallint")
     */
    private ?int $type = null;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private ?bool $isTool = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private ?bool $isOpenMapWeapon = false;

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
     * @return Weapons
     */
    public function setImageName(?string $imageName): Weapons
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
     * @return Weapons
     */
    public function setIsTool(?bool $isTool): Weapons
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
     * @return Weapons
     */
    public function setIsOpenMapWeapon(?bool $isOpenMapWeapon): Weapons
    {
        $this->isOpenMapWeapon = $isOpenMapWeapon;
        return $this;
    }

}
