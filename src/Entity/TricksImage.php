<?php

namespace App\Entity;

use App\Repository\TricksImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: TricksImageRepository::class)]
#[Vich\Uploadable]
class TricksImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tricksImage')]
    #[ORM\JoinColumn(nullable: true)]

    private ?Tricks $tricks = null;

    #[Vich\UploadableField(mapping: 'tricks_images', fileNameProperty: 'imageName')]

    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]

    private ?string $imageName = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]

    private \DateTimeImmutable $updatedAt;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();

        //end __construct()
    }

   /**
    * Get tricks Image id
    *
    * @return integer|null
    */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get tricks Image tricks
     *
     * @return Tricks|null
     */
    public function getTricks(): ?Tricks
    {
        return $this->tricks;
    }

    /**
     * Get tricks Image tricks
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
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * Get tricks Image file
     *
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * Set tricks Image name
     *
     * @param string|null $imageName
     * @return void
     */
    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    /**
     * Get tricks Image name
     *
     * @return string|null
     */
    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    /**
     * Get tricks Image updatedAt
     *
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Set tricks Image updatedAt
     *
     * @param \DateTimeImmutable $updatedAt
     * @return self
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
