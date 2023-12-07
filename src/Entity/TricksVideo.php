<?php

namespace App\Entity;

use App\Repository\TricksVideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TricksVideoRepository::class)]
class TricksVideo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tricksVideo')]
    #[ORM\JoinColumn(nullable: false)]

    private ?Tricks $tricks = null;

    #[ORM\Column(nullable: true)]

    private ?string $videoUrl = null;

    /**
     * Get tricks Video id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get tricks Video tricks
     *
     * @return Tricks|null
     */
    public function getTricks(): ?Tricks
    {
        return $this->tricks;
    }

    /**
     * Set tricks Video tricks
     *
     * @param Tricks|null $tricks
     * @return self
     */
    public function setTricks(?Tricks $tricks): self
    {
        $this->tricks = $tricks;
        return $this;
    }

    /**
     * Get tricks Video url
     *
     * @return string|null
     */
    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    /**
     * Set tricks Video url
     *
     * @param string|null $videoUrl
     * @return self
     */
    public function setVideoUrl(?string $videoUrl): self
    {
        $this->videoUrl = $videoUrl;
        return $this;
    }
}
