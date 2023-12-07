<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]

    private ?string $content = null;

    #[ORM\Column]

    private ?bool $isApproved = false;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]

    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]

    private ?Tricks $tricks = null;

    #[ORM\Column]

    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
    //end __construct()

    
    /**
     * Get Comment id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get Comment content
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set Comment content
     *
     * @param string $content
     * @return static
     */
    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get Comment approved status
     *
     * @return boolean|null
     */
    public function isIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    /**
     * Set Comment approved status
     *
     * @param boolean $isApproved
     * @return static
     */
    public function setIsApproved(bool $isApproved): static
    {
        $this->isApproved = $isApproved;

        return $this;
    }


    /**
     * Get Comment author
     *
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Set Comment author
     *
     * @param User|null $author
     * @return static
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get Comment tricks
     *
     * @return Tricks|null
     */
    public function getTricks(): ?Tricks
    {
        return $this->tricks;
    }

    /**
     * Set Comment tricks
     *
     * @param Tricks|null $tricks
     * @return static
     */
    public function setTricks(?Tricks $tricks): static
    {
        $this->tricks = $tricks;

        return $this;
    }

    /**
     * Get Comment createdAt
     *
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set Comment createdAt
     *
     * @param \DateTimeImmutable $createdAt
     * @return static
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
