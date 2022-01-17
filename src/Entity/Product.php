<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route("api_products_get_details", parameters = {"id" = "expr(object.getId())"}, absolute = true),
 *     exclusion = @Hateoas\Exclusion(groups={"details","list"}),
 * )
 *
 * @Hateoas\Relation(
 *     "list",
 *     href = @Hateoas\Route("api_products_get_list", absolute = true),
 *     exclusion = @Hateoas\Exclusion(groups={"details"}),
 * )
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"details", "list"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column
     * @Serializer\Groups({"details", "list"})
     */
    private string $name;

    /**
     * @ORM\Column
     * @Serializer\Groups({"details"})
     */
    private string $brand;

    /**
     * @ORM\Column
     * @Serializer\Groups({"details"})
     */
    private string $model;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"details"})
     */
    private string $description;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"details", "list"})
     */
    private int $price;

    /**
     * @ORM\Column
     * @Serializer\Groups({"details"})
     */
    private string $reference;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({"details"})
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
