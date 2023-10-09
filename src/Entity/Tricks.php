<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TricksRepository::class)]
class Tricks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'tricks', targetEntity: TricksImage::class, orphanRemoval: true)]
    private Collection $tricksImage;

    #[ORM\OneToMany(mappedBy: 'tricks', targetEntity: TricksVideo::class, orphanRemoval: true)]
    private Collection $tricksVideo;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'tricks')]
    private Collection $id_group;

    public function __construct()
    {
        $this->tricksImage = new ArrayCollection();
        $this->tricksVideo = new ArrayCollection();
        $this->id_group = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, TricksImage>
     */
    public function getTricksImage(): Collection
    {
        return $this->tricksImage;
    }

    public function addTricksImage(TricksImage $tricksImage): static
    {
        if (!$this->tricksImage->contains($tricksImage)) {
            $this->tricksImage->add($tricksImage);
            $tricksImage->setTricks($this);
        }

        return $this;
    }

    public function removeTricksImage(TricksImage $tricksImage): static
    {
        if ($this->tricksImage->removeElement($tricksImage)) {
            // set the owning side to null (unless already changed)
            if ($tricksImage->getTricks() === $this) {
                $tricksImage->setTricks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TricksVideo>
     */
    public function getTricksVideo(): Collection
    {
        return $this->tricksVideo;
    }

    public function addTricksVideo(TricksVideo $tricksVideo): static
    {
        if (!$this->tricksVideo->contains($tricksVideo)) {
            $this->tricksVideo->add($tricksVideo);
            $tricksVideo->setTricks($this);
        }

        return $this;
    }

    public function removeTricksVideo(TricksVideo $tricksVideo): static
    {
        if ($this->tricksVideo->removeElement($tricksVideo)) {
            // set the owning side to null (unless already changed)
            if ($tricksVideo->getTricks() === $this) {
                $tricksVideo->setTricks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getIdGroup(): Collection
    {
        return $this->id_group;
    }

    public function addIdGroup(Group $idGroup): static
    {
        if (!$this->id_group->contains($idGroup)) {
            $this->id_group->add($idGroup);
        }

        return $this;
    }

    public function removeIdGroup(Group $idGroup): static
    {
        $this->id_group->removeElement($idGroup);

        return $this;
    }
}
