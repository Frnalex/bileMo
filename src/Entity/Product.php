<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"getAll", "getItem"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column
     * @Groups({"getAll", "getItem"})
     */
    private string $name;

    /**
     * @ORM\Column
     * @Groups({"getItem"})
     */
    private string $brand;

    /**
     * @ORM\Column
     * @Groups({"getItem"})
     */
    private string $model;

    /**
     * @ORM\Column(type="text")
     * @Groups({"getItem"})
     */
    private string $description;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"getAll", "getItem"})
     */
    private int $price;

    /**
     * @ORM\Column
     * @Groups({"getItem"})
     */
    private string $reference;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"getItem"})
     */
    private DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
