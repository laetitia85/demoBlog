<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    // on précise le nombre de caractères min et max que le titre peut contenir
    #[Assert\Length(min:5, max:255, minMessage:"Le titre doit comporter au moins {{ limit }} caractères.")]
    // on précise avec la contrainte NotNull() qu'on ne veut pas que le champ soit null et on ajoute un msg en paramètre
    #[Assert\NotNull(message:"Le champ titre ne peut pas être vide! Veuillez insérer un titre")]
    private $title;

    #[ORM\Column(type: 'text')]
    // on précise le nombre de caractères min que le contenu peut contenir
    #[Assert\Length(min:10, minMessage:"Le contenu doit comporter au moins {{ limit }} caractères.")]
    // on précise avec la contrainte NotNull() qu'on ne veut pas que le champ soit null et on ajoute un msg en paramètre
    #[Assert\NotNull(message:"Le champ contenu ne peut pas être vide! Veuillez insérer un contenu")]
    private $content;

    #[ORM\Column(type: 'string', length: 255)]
    // on précise avec la contrainte NotNull() qu'on ne veut pas que le champ soit null et on ajoute un msg en paramètre
    #[Assert\NotNull(message:"Le champ image ne peut pas être vide! Veuillez insérer l'url de l'image")]
    private $image;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
