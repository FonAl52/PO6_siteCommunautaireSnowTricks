<?php

namespace App\Entity;

use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\Criteria;

#[UniqueEntity('title')]
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: TricksRepository::class)]
class Tricks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]

    private ?string $title;

    #[ORM\Column(length: 255)]

    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]

    private ?string $description;

    #[ORM\Column]

    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]

    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]

    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'tricks', targetEntity: TricksImage::class, orphanRemoval: true, fetch: 'EAGER', cascade: ["persist", "remove"])]

    private Collection $tricksImage;

    #[ORM\OneToMany(mappedBy: 'tricks', targetEntity: TricksVideo::class, orphanRemoval: true, fetch: 'EAGER', cascade: ["persist", "remove"])]

    private Collection $tricksVideo;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'tricks')]
    
    private Collection $id_group;

    #[ORM\OneToMany(mappedBy: 'tricks', targetEntity: Comments::class, orphanRemoval: true)]

    private Collection $comments;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();

        $this->tricksImage = new ArrayCollection();
        $this->tricksVideo = new ArrayCollection();
        $this->id_group = new ArrayCollection();
        $this->comments = new ArrayCollection();

        //end __construct()
    }

    
    /**
     * set updatedAt
     *
     * @return void
     */
    #[ORM\PrePersist()]
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * get tricks id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * get tricks title
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * set tricks title
     *
     * @param string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * get tricks slug
     *
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * set tricks slug
     *
     * @param string $slug
     * @return static
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * get tricks description
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * set tricks description
     *
     * @param string|null $description
     * @return static
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * get tricks createdAt
     *
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * set tricks createdAt
     *
     * @param \DateTimeImmutable $createdAt
     * @return static
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * get tricks updatedAt
     *
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * set tricks updatedAt
     *
     * @param \DateTimeImmutable|null $updatedAt
     * @return static
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * get the user who create tricks
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * set the user who create tricks
     *
     * @param User|null $user
     * @return static
     */
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
    
    /**
     * add images for tricks
     *
     * @param TricksImage $tricksImage
     * @return static
     */
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
    
    /**
     * add videos for tricks
     *
     * @param TricksVideo $tricksVideo
     * @return static
     */
    public function addTricksVideo(TricksVideo $tricksVideo): static
    {
        if (!$this->tricksVideo->contains($tricksVideo)) {
            $this->tricksVideo->add($tricksVideo);
            $tricksVideo->setTricks($this);
        }

        return $this;
    }

    /**
     * remove tricks videos
     *
     * @param TricksVideo $tricksVideo
     * @return static
     */
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

    /**
     * add id for tricks groups
     *
     * @param Group $idGroup
     * @return static
     */
    public function addIdGroup(Group $idGroup): static
    {
        if (!$this->id_group->contains($idGroup)) {
            $this->id_group->add($idGroup);
        }

        return $this;
    }

    /**
     * remove id for tricks groups
     *
     * @param Group $idGroup
     * @return static
     */
    public function removeIdGroup(Group $idGroup): static
    {
        $this->id_group->removeElement($idGroup);

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * add comments on tricks
     *
     * @param Comment $comment
     * @return static
     */
    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    /**
     * sort Comments
     *
     * @return Collection
     */
    public function getSortedComments(): Collection
    {
        $criteria = Criteria::create()->orderBy([ 'id' => Criteria::DESC ]);

        return $this->comments->matching($criteria);
    }

    /**
     * remove comments on tricks
     *
     * @param Comment $comment
     * @return static
     */
    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
}
