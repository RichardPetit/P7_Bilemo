<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"customerDetail", "customerList"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customerDetail", "customerList"})
     * @Assert\NotBlank(message="Le nom ne peut etre vide")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customerDetail", "customerList"})
     * @Assert\NotBlank(message="L'email ne peut etre vide")
     * @Assert\Email(message="Le format email n'est pas respecté")
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le mot de passe ne peut etre vide")
     */
    private $password;


    /**
     * @ORM\ManyToMany(targetEntity=Phone::class, inversedBy="customers")
     * @Groups({"customerDetail", "customerList"})
     */
    private $phones;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->phones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @return Collection|Phone[]
     */
    public function getPhones(): ?Collection
    {
        return $this->phones;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        $this->phones->removeElement($phone);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
