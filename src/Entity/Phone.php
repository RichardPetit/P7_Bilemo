<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 * @OA\Schema()
 */
class Phone
{
    public const GROUP_LIST = "phoneList";
    public const GROUP_DETAIL = "phoneDetail";
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"phoneList"})
     * @OA\Property(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"phoneDetail", "phoneList"})
     * @Assert\NotBlank(message="Le champs de la marque ne peut etre vide")
     * @OA\Property(type="string")
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"phoneDetail", "phoneList"})
     * @Assert\NotBlank(message="Le nom ne peut etre vide")
     * @OA\Property(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"phoneDetail", "phoneList"})
     * @Assert\NotBlank(message="Le prix ne peut etre vide")
     * @OA\Property(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Groups({"phoneDetail", "phoneList"})
     * @Assert\NotBlank(message="Le contenu ne peut etre vide")
     * @OA\Property(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"phoneDetail", "phoneList"})
     * @Assert\NotBlank(message="La couleur ne peut etre vide")
     * @OA\Property(type="string")
     */
    private $color;

    /**
     * @ORM\ManyToMany(targetEntity=Customer::class, mappedBy="phones")
     */
    private $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->addPhone($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            $customer->removePhone($this);
        }

        return $this;
    }
}
