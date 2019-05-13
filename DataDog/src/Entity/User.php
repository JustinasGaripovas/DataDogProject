<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank(message="Vardas negali būti tuščias")
     * @Assert\Length(
     *      max = 60,
     *      min = 3,
     *      maxMessage = "Vardas per ilgas [max {{ limit }} raidžių ]",
     *      minMessage = "Vardas per trumpas [min {{ limit }} raidžių ]"
     *      )
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    //TODO: Extra validation when changing password
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Pašto adresas negali būti tuščias")
     * @Assert\Email(
     *     message = "Netinkamas pašto adresas",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_token;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="users")
     */
    private $subscribed_categories;

    public function __construct()
    {
        $this->subscribed_categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getSubscribedCategories(): Collection
    {
        return $this->subscribed_categories;
    }

    public function addSubscribedCategory(Category $subscribedCategory): self
    {
        if (!$this->subscribed_categories->contains($subscribedCategory)) {
            $this->subscribed_categories[] = $subscribedCategory;
        }

        return $this;
    }

    public function removeSubscribedCategory(Category $subscribedCategory): self
    {
        if ($this->subscribed_categories->contains($subscribedCategory)) {
            $this->subscribed_categories->removeElement($subscribedCategory);
        }

        return $this;
    }
}
