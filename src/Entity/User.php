<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route("api_users_get_details", parameters = {"id" = "expr(object.getId())"}, absolute = true),
 *     exclusion = @Hateoas\Exclusion(groups={"details","list"}),
 * )
 *
 * @Hateoas\Relation(
 *     "delete",
 *     href = @Hateoas\Route("api_users_delete", parameters = {"id" = "expr(object.getId())"}, absolute = true),
 *     exclusion = @Hateoas\Exclusion(groups={"details","list"}),
 * )
 *
 * @Hateoas\Relation(
 *     "list",
 *     href = @Hateoas\Route("api_users_get_list", absolute = true),
 *     exclusion = @Hateoas\Exclusion(groups={"details"}),
 * )
 */
class User
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
     * @Assert\NotBlank
     * @Assert\Email
     * @Serializer\Groups({"details", "list"})
     */
    private string $email;

    /**
     * @ORM\Column
     * @Assert\NotBlank
     * @Serializer\Groups({"details"})
     */
    private string $firstName;

    /**
     * @ORM\Column
     * @Assert\NotBlank
     * @Serializer\Groups({"details"})
     */
    private string $lastName;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Serializer\Groups({"details"})
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Client")
     */
    private Client $client;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
