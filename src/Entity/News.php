<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: Types::INTEGER)]
private ?int $id = null;

#[ORM\Column(length: 100)]
#[Assert\NotBlank(message: "Le titre ne peut pas être vide")]
#[Assert\Length(
min: 2,
max: 100,
minMessage: "Le titre doit contenir au moins {{ limit }} caractères",
maxMessage: "Le titre ne peut pas contenir plus de {{ limit }} caractères"
)]
private ?string $title = null;

#[ORM\Column(type: Types::TEXT)]
#[Assert\NotBlank(message: "Le texte ne peut pas être vide")]
#[Assert\Length(
min: 2,
max: 5000,
minMessage: "Le texte doit contenir au moins {{ limit }} caractères",
maxMessage: "Le texte ne peut pas contenir plus de {{ limit }} caractères"
)]
private ?string $content = null;

#[ORM\Column(type: Types::DATETIME_MUTABLE)]
private ?\DateTimeInterface $dateCreation = null;

#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
private ?\DateTimeInterface $dateEdition = null;

#[ORM\ManyToOne(inversedBy: 'news')]
#[ORM\JoinColumn(nullable: false)]
private ?User $user = null;

#[ORM\Column(length: 255, unique: true)]
private ?string $slug = null;

#[ORM\ManyToOne(inversedBy: 'news')]
#[ORM\JoinColumn(nullable: false)]
private ?ChocolateShop $chocolateShop = null;

// Getter and setter methods...

public function getId(): ?int
{
return $this->id;
}

public function getTitle(): ?string
{
return $this->title;
}

public function setTitle(?string $title): self
{
$this->title = $title;
return $this;
}

public function getContent(): ?string
{
return $this->content;
}

public function setContent(?string $content): self
{
$this->content = $content;
return $this;
}

public function getDateCreation(): ?\DateTimeInterface
{
return $this->dateCreation;
}

public function setDateCreation(\DateTimeInterface $dateCreation): self
{
$this->dateCreation = $dateCreation;
return $this;
}

public function getDateEdition(): ?\DateTimeInterface
{
return $this->dateEdition;
}

public function setDateEdition(?\DateTimeInterface $dateEdition): self
{
$this->dateEdition = $dateEdition;
return $this;
}

public function getUser(): ?User
{
return $this->user;
}

public function setUser(?User $user): self
{
$this->user = $user;
return $this;
}

public function getSlug(): ?string
{
return $this->slug;
}

public function setSlug(?string $slug): self
{
$this->slug = $slug;
return $this;
}

public function getChocolateShop(): ?ChocolateShop
{
return $this->chocolateShop;
}

public function setChocolateShop(?ChocolateShop $chocolateShop): self
{
$this->chocolateShop = $chocolateShop;
return $this;
}
}