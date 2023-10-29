<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use 
Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $first_name = null;

    #[ORM\Column(length: 100)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(length: 100)]
    private ?string $job_title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $profile_picture = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $cover_picture = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $facebook_link = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $twitter_link = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $instagram_link = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $linkedin_link = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: News::class, orphanRemoval: true)]
    private Collection $news;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comment;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class, orphanRemoval: true)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notification;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ChocolateShop $chocolate_shop = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;
    
    #[ORM\Column(type: "boolean")]
    private $isApproved = false;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tokenRegistration = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $tokenRegistrationLifeTime = null;


    public function __construct()
    {
        $this->news = new ArrayCollection();
        $this->comment = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->notification = new ArrayCollection();
        $this->isVerified = false;
        $this->tokenRegistrationLifeTime = (new DateTime('now'))->add(new DateInterval('P1D'));

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->job_title;
    }

    public function setJobTitle(string $job_title): static
    {
        $this->job_title = $job_title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profile_picture;
    }

    public function setProfilePicture(?string $profile_picture): static
    {
        $this->profile_picture = $profile_picture;

        return $this;
    }

    public function getCoverPicture(): ?string
    {
        return $this->cover_picture;
    }

    public function setCoverPicture(?string $cover_picture): static
    {
        $this->cover_picture = $cover_picture;

        return $this;
    }

    public function getFacebookLink(): ?string
    {
        return $this->facebook_link;
    }

    public function setFacebookLink(?string $facebook_link): static
    {
        $this->facebook_link = $facebook_link;

        return $this;
    }

    public function getTwitterLink(): ?string
    {
        return $this->twitter_link;
    }

    public function setTwitterLink(?string $twitter_link): static
    {
        $this->twitter_link = $twitter_link;

        return $this;
    }

    public function getInstagramLink(): ?string
    {
        return $this->instagram_link;
    }

    public function setInstagramLink(?string $instagram_link): static
    {
        $this->instagram_link = $instagram_link;

        return $this;
    }

    public function getLinkedinLink(): ?string
    {
        return $this->linkedin_link;
    }

    public function setLinkedinLink(?string $linkedin_link): static
    {
        $this->linkedin_link = $linkedin_link;

        return $this;
    }

    /**
     * @return Collection<int, News>
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): static
    {
        if (!$this->news->contains($news)) {
            $this->news->add($news);
            $news->setUser($this);
        }

        return $this;
    }

    public function removeNews(News $news): static
    {
        if ($this->news->removeElement($news)) {
            // set the owning side to null (unless already changed)
            if ($news->getUser() === $this) {
                $news->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }
        return $this;
    }
    /**
     * @return Collection<int, Notification>
     */
    public function getNotification(): Collection
    {
        return $this->notification;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notification->contains($notification)) {
            $this->notification->add($notification);
            $notification->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
            return $this;
        }
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notification->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
            return $this;
        }
    }
    public function getChocolateShop(): ?ChocolateShop
    {
        return $this->chocolate_shop;
    }

    public function setChocolateShop(?ChocolateShop $chocolate_shop): static
    {
        $this->chocolate_shop = $chocolate_shop;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    // Setter pour définir la valeur de $is_verified
    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function getIsApproved(): bool
    {
    return $this->isApproved;
    }


    public function setApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;
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

    public function eraseCredentials()
    {
        // Vous pouvez laisser cette méthode vide si vous n'avez pas de données sensibles à effacer
    }

    public function getUserIdentifier(): string
    {
        // Utilisez l'adresse e-mail comme identifiant de l'utilisateur
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getTokenRegistration(): ?string
    {
        return $this->tokenRegistration;
    }

    public function setTokenRegistration(?string $tokenRegistration): static
    {
        $this->tokenRegistration = $tokenRegistration;

        return $this;
    }

    public function getTokenRegistrationLifeTime(): ?\DateTimeInterface
    {
        return $this->tokenRegistrationLifeTime;
    }

    public function setTokenRegistrationLifeTime(\DateTimeInterface $tokenRegistrationLifeTime): static
    {
        $this->tokenRegistrationLifeTime = $tokenRegistrationLifeTime;

        return $this;
    }

}

