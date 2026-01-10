<?php

namespace App\Entity;
use App\Validator\BanWord;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Vich\UploaderBundle\Mapping\Attribute\UploadableField;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Attribute\Uploadable;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[UniqueEntity('title')]
#[UniqueEntity('slug')]
#[Uploadable()]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['recipes.index'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Length(min: 5, max: 255)]
    #[NotBlank(message: 'Le titre ne peut pas être vide.')]
    #[BanWord()]
    #[Groups(['recipes.index', 'recipes.create'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Length(min: 5, max: 255)]
    #[Regex(pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Le slug ne peut contenir que des lettres minuscules, des chiffres et des tirets.')]
    #[Groups(['recipes.index', 'recipes.create'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Length(min: 30)]
    #[Groups(['recipes.show', 'recipes.create'])]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    #[Positive(message: 'La durée doit être un nombre positif.')]
    #[Groups(['recipes.index', 'recipes.create'])]
    private ?int $duration = null;

    #[ORM\ManyToOne(inversedBy: 'recipes',cascade: ['persist'])]
     #[Groups(['recipes.show'])]
    private ?Category $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[UploadableField(mapping: 'recipes', fileNameProperty: 'thumbnail')]
    #[Image()]
    private ?File $thumbnailFile = null;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    private ?User $user = null;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(?File $thumbnailFile): static
    {
        $this->thumbnailFile = $thumbnailFile;

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
}
