<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    public const TOP_UP_LIMIT = 100000;
    public const TRANSFER_LIMIT = 10000;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Balance $balance = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $type = null;

    /**
     * @var string|null По легенде в мету попадает любая доп информация/контекст актуальная для конкретной транзакции.
     * Например при попоплнения мы можем указать номер банкомата, отеделение кассы, при переводе, - кому и т.д
     * Строка сделана для упрощения, формат данных может быть любой.
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $meta = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): ?Balance
    {
        return $this->balance;
    }

    public function setBalance(?Balance $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getType(): TransactionType
    {
        return TransactionType::from($this->type);
    }

    public function setType(TransactionType $type): static
    {
        $this->type = $type->value;

        return $this;
    }

    public function getMeta(): ?string
    {
        return $this->meta;
    }

    public function setMeta(string $meta): static
    {
        $this->meta = $meta;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
