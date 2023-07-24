<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\ManyToOne(inversedBy: 'order')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    #[ORM\Column]
    private ?float $totalPriceHt = null;

    #[ORM\Column]
    private ?float $totalPriceTtc = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $items = [];

    #[ORM\Column(length: 255)]
    private ?string $ship_addr = null;

    #[ORM\Column(length: 255)]
    private ?string $ship_city = null;

    #[ORM\Column(length: 255)]
    private ?string $ship_zipcode = null;

    #[ORM\Column(length: 255)]
    private ?string $bill_addr = null;

    #[ORM\Column(length: 255)]
    private ?string $bill_city = null;

    #[ORM\Column(length: 255)]
    private ?string $bill_zipcode = null;

    #[ORM\Column(length: 255)]
    private ?string $ship_lname = null;

    #[ORM\Column(length: 255)]
    private ?string $ship_fname = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getTotalPriceHt(): ?float
    {
        return $this->totalPriceHt;
    }

    public function setTotalPriceHt(float $totalPriceHt): self
    {
        $this->totalPriceHt = $totalPriceHt;

        return $this;
    }

    public function getTotalPriceTtc(): ?float
    {
        return $this->totalPriceTtc;
    }

    public function setTotalPriceTtc(float $totalPriceTtc): self
    {
        $this->totalPriceTtc = $totalPriceTtc;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getShipAddr(): ?string
    {
        return $this->ship_addr;
    }

    public function setShipAddr(string $ship_addr): self
    {
        $this->ship_addr = $ship_addr;

        return $this;
    }

    public function getShipCity(): ?string
    {
        return $this->ship_city;
    }

    public function setShipCity(string $ship_city): self
    {
        $this->ship_city = $ship_city;

        return $this;
    }

    public function getShipZipcode(): ?string
    {
        return $this->ship_zipcode;
    }

    public function setShipZipcode(string $ship_zipcode): self
    {
        $this->ship_zipcode = $ship_zipcode;

        return $this;
    }

    public function getBillAddr(): ?string
    {
        return $this->bill_addr;
    }

    public function setBillAddr(string $bill_addr): self
    {
        $this->bill_addr = $bill_addr;

        return $this;
    }

    public function getBillCity(): ?string
    {
        return $this->bill_city;
    }

    public function setBillCity(string $bill_city): self
    {
        $this->bill_city = $bill_city;

        return $this;
    }

    public function getBillZipcode(): ?string
    {
        return $this->bill_zipcode;
    }

    public function setBillZipcode(string $bill_zipcode): self
    {
        $this->bill_zipcode = $bill_zipcode;

        return $this;
    }

    public function getShipLname(): ?string
    {
        return $this->ship_lname;
    }

    public function setShipLname(string $ship_lname): self
    {
        $this->ship_lname = $ship_lname;

        return $this;
    }

    public function getShipFname(): ?string
    {
        return $this->ship_fname;
    }

    public function setShipFname(string $ship_fname): self
    {
        $this->ship_fname = $ship_fname;

        return $this;
    }
}
