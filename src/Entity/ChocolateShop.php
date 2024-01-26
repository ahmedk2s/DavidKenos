<?php

namespace App\Entity;

use App\Repository\ChocolateShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChocolateShopRepository::class)]
class ChocolateShop
{
#[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: 'integer')]
private ?int $id = null;

#[ORM\Column(length: 50)]
private ?string $city = null;

#[ORM\OneToMany(mappedBy: 'chocolateShop', targetEntity: News::class, orphanRemoval: true)]
private Collection $news;

#[ORM\OneToMany(mappedBy: 'chocolateShop', targetEntity: User::class, orphanRemoval: true)]
private Collection $users;

#[ORM\Column(length: 255, unique: true)]
private ?string $slug = null;

public function __construct()
{
$this->news = new ArrayCollection();
$this->users = new ArrayCollection();
}

// Getter and setter methods...

public function getId(): ?int
{
return $this->id;
}

public function getCity(): ?string
{
return $this->city;
}

public function setCity(string $city): self
{
$this->city = $city;
return $this;
}

/**
* @return Collection<int, News>
    */
    public function getNews(): Collection
    {
    return $this->news;
    }

    public function addNews(News $news): self
    {
    if (!$this->news->contains($news)) {
    $this->news->add($news);
    $news->setChocolateShop($this);
    }

    return $this;
    }

    public function removeNews(News $news): self
    {
    if ($this->news->removeElement($news)) {
    // set the owning side to null (unless already changed)
    if ($news->getChocolateShop() === $this) {
    $news->setChocolateShop(null);
    }
    }

    return $this;
    }

    /**
    * @return Collection<int, User>
        */
        public function getUsers(): Collection
        {
        return $this->users;
        }

        public function addUser(User $user): self
        {
        if (!$this->users->contains($user)) {
        $this->users->add($user);
        $user->setChocolateShop($this);
        }

        return $this;
        }

        public function removeUser(User $user): self
        {
        if ($this->users->removeElement($user)) {
        // set the owning side to null (unless already changed)
        if ($user->getChocolateShop() === $this) {
        $user->setChocolateShop(null);
        }
        }

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

        public function __toString(): string
        {
        return $this->city;
        }
        }