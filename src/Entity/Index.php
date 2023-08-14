<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\IndexHistory;
use App\Repository\IndexRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * Index class.
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'method' => 'GET',
            'path' => '/indexes',
            'controller' => IndexHistory::class,
            'security' => "is_granted('IS_AUTHENTICATED_FULLY')",
        ],
    ],
    itemOperations: []
)]
#[ORM\Entity(repositoryClass: IndexRepository::class)]
#[ORM\Table(name: '`indexes`')]
class Index
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tag = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 15, scale: 1)]
    private ?string $value = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getTag(): ?string
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function setTag(string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return null|\DateTimeImmutable
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param \DateTimeImmutable $created_at
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
