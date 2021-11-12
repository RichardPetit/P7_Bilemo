<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Annotations as OA;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @OA\Schema()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"userList"})
     * @OA\Property(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"userDetail", "userList"})
     * @Assert\NotBlank(message="Le prénom ne peut etre vide")
     * @OA\Property(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"userDetail", "userList"})
     * @Assert\NotBlank(message="Le nom ne peut etre vide")
     * @OA\Property(type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"userDetail", "userList"})
     * @Assert\NotBlank(message="L'email ne peut etre vide")
     * @Assert\Email(message="Le format email n'est pas respecté")
     * @OA\Property(type="string")
     */
    private $email;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"userDetail", "userList"})
     * @OA\Property(type="string", format="date-time")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="user")
     */
    private $customers;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Le mot de passe ne peut etre vide")
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    /**
     * @param mixed $id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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
            $customer->setUser($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getUser() === $this) {
                $customer->setUser(null);
            }
        }

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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}
