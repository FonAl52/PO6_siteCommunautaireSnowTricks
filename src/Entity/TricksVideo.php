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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTricks(): ?Tricks
    {
        return $this->tricks;
    }

    public function setTricks(?Tricks $tricks): self
    {
        $this->tricks = $tricks;
        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl(?string $videoUrl): self
    {
        $this->videoUrl = $videoUrl;
        return $this;
    }
}
