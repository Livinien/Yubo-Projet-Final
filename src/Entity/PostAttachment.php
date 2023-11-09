<?php

namespace App\Entity;

use App\Entity\Post;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostAttachmentRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PostAttachmentRepository::class)]
#[Vich\Uploadable]
class PostAttachment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'post_attachment', fileNameProperty: 'imageName', mimeType: 'mimeType', originalName: 'originalName')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255)]
    private ?string $imageName = null;
    
    #[ORM\Column(length: 255)]
    private ?string $mimeType = null;
    
    #[ORM\Column(length: 255)]
    private ?string $originalName = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\OneToOne(inversedBy: 'postAttachment', cascade: ['persist', 'remove'])]
    private ?Post $post = null;

    public function __toString(): string
    {
        return (string) $this->originalName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $file = null): self
    {
        $this->imageFile = $file;
        if (null !== $file) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $fileName): static
    {
        $this->imageName = $fileName;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }
}