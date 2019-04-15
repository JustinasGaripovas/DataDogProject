<?php

namespace App\Entity;


use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank(message="Pavadinimas negali būti tuščias")
     * @Assert\Length(
     *      max = 60,
     *      min = 3,
     *      maxMessage = "Pavadinimas per ilgas [max {{ limit }} raidžių ]",
     *      minMessage = "Pavadinimas per trumpas [min {{ limit }} raidžių ]"
     *      )
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="Aprašas negali būti tuščias")
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Aprašas per ilgas [max {{ limit }} raidžių ]"
     *      )
     */
    private $excerpt;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="Aprašas negali būti tuščias")
     * @Assert\Length(
     *      max = 600,
     *      maxMessage = "Aprašas per ilgas [max {{ limit }} raidžių ]"
     *      )
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\GreaterThanOrEqual(
     *   value = 0,
     *   message = "Kaina genali būti {{ value }} ji turi būti {{ compared_value }} arba aukščiau"
     *  )
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lng;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Category")
     */
    private $eventCategories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="event", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->eventCategories = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?float
    {
        return $this->lng;
    }

    public function setLng(?float $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }


    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    public function addEventCategory(Category $category)
    {
        $this->eventCategories[] = $category;
    }

    public function getEventCategories()
    {
        return $this->eventCategories;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setEvent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getEvent() === $this) {
                $comment->setEvent(null);
            }
        }

        return $this;
    }

}
