<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Kategorija negali būti tuščias")
     * @Assert\Length(
     *      max = 60,
     *      min = 3,
     *      maxMessage = "Kategorija per ilga [max {{ limit }} raidžių ]",
     *      minMessage = "Kategorija per trumpa [min {{ limit }} raidžių ]"
     *      )
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="subscribed_categories")
     */
    private $users;

    public function __toString()
    {
       return "{$this->name}";
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addSubscribedCategory($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeSubscribedCategory($this);
        }

        return $this;
    }
}
