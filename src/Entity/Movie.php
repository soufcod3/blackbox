<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $synopsis;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $poster;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trailerLink;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $myRate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $popularity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $myReview;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="movies")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Actor::class, mappedBy="movies")
     */
    private $actors;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $watchedOn;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="movie")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="moviesWatchlist")
     */
    private $viewers;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="favoriteMovies")
     */
    private $lovers;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="seenMovies")
     */
    private $seeners;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->viewers = new ArrayCollection();
        $this->lovers = new ArrayCollection();
        $this->seeners = new ArrayCollection();
    }

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

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getTrailerLink(): ?string
    {
        return $this->trailerLink;
    }

    public function setTrailerLink(string $trailerLink): self
    {
        $this->trailerLink = $trailerLink;

        return $this;
    }

    public function getMyRate(): ?int
    {
        return $this->myRate;
    }

    public function setMyRate(int $myRate): self
    {
        $this->myRate = $myRate;

        return $this;
    }

    public function getPopularity(): ?string
    {
        return $this->popularity;
    }

    public function setPopularity(string $popularity): self
    {
        $this->popularity = $popularity;

        return $this;
    }

    public function getMyReview(): ?string
    {
        return $this->myReview;
    }

    public function setMyReview(string $myReview): self
    {
        $this->myReview = $myReview;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Actor[]
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors[] = $actor;
            $actor->addMovie($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        if ($this->actors->removeElement($actor)) {
            $actor->removeMovie($this);
        }

        return $this;
    }

    public function getWatchedOn(): ?\DateTimeInterface
    {
        return $this->watchedOn;
    }

    public function setWatchedOn(\DateTimeInterface $watchedOn): self
    {
        $this->watchedOn = $watchedOn;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setMovie($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getMovie() === $this) {
                $comment->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getViewers(): Collection
    {
        return $this->viewers;
    }

    public function addViewer(User $viewer): self
    {
        if (!$this->viewers->contains($viewer)) {
            $this->viewers[] = $viewer;
            $viewer->addMoviesWatchlist($this);
        }

        return $this;
    }

    public function removeViewer(User $viewer): self
    {
        if ($this->viewers->removeElement($viewer)) {
            $viewer->removeMoviesWatchlist($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getLovers(): Collection
    {
        return $this->lovers;
    }

    public function addLover(User $lover): self
    {
        if (!$this->lovers->contains($lover)) {
            $this->lovers[] = $lover;
            $lover->addFavoriteMovie($this);
        }

        return $this;
    }

    public function removeLover(User $lover): self
    {
        if ($this->lovers->removeElement($lover)) {
            $lover->removeFavoriteMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getSeeners(): Collection
    {
        return $this->seeners;
    }

    public function addSeener(User $seener): self
    {
        if (!$this->seeners->contains($seener)) {
            $this->seeners[] = $seener;
            $seener->addSeenMovie($this);
        }

        return $this;
    }

    public function removeSeener(User $seener): self
    {
        if ($this->seeners->removeElement($seener)) {
            $seener->removeSeenMovie($this);
        }

        return $this;
    }

}
