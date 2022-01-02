<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Series::class, inversedBy="viewers")
     * @ORM\JoinTable(name="watchlistSeries")
     */
    private $seriesWatchlist;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, inversedBy="viewers")
     * @ORM\JoinTable(name="watchlistMovies")
     */
    private $moviesWatchlist;

    /**
     * @ORM\ManyToMany(targetEntity=Series::class, inversedBy="lovers")
     * @ORM\JoinTable(name="favoriteSeries")
     */
    private $favoriteSeries;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, inversedBy="lovers")
     * @ORM\JoinTable(name="favoriteMovies")
     */
    private $favoriteMovies;

    /**
     * @ORM\ManyToMany(targetEntity=Series::class, inversedBy="seeners")
     * @ORM\JoinTable(name="seenSeries")
     */
    private $seenSeries;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, inversedBy="seeners")
     * @ORM\JoinTable(name="seenMovies")
     */
    private $seenMovies;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->watchlist = new ArrayCollection();
        $this->moviesWatchlist = new ArrayCollection();
        $this->seriesWatchlist = new ArrayCollection();
        $this->favoriteSeries = new ArrayCollection();
        $this->favoriteMovies = new ArrayCollection();
        $this->seenSeries = new ArrayCollection();
        $this->seenMovies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Series[]
     */
    public function getSeriesWatchlist(): Collection
    {
        return $this->seriesWatchlist;
    }

    public function addSeriesWatchlist(Series $seriesWatchlist): self
    {
        if (!$this->seriesWatchlist->contains($seriesWatchlist)) {
            $this->seriesWatchlist[] = $seriesWatchlist;
        }

        return $this;
    }

    public function removeSeriesWatchlist(Series $seriesWatchlist): self
    {
        $this->seriesWatchlist->removeElement($seriesWatchlist);

        return $this;
    }

    public function isInSeriesWatchlist(Series $series): bool
    {
        if ($this->seriesWatchlist->contains($series)) {
            return true;
        }
        return false;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMoviesWatchlist(): Collection
    {
        return $this->moviesWatchlist;
    }

    public function addMoviesWatchlist(Movie $moviesWatchlist): self
    {
        if (!$this->moviesWatchlist->contains($moviesWatchlist)) {
            $this->moviesWatchlist[] = $moviesWatchlist;
        }

        return $this;
    }

    public function removeMoviesWatchlist(Movie $moviesWatchlist): self
    {
        $this->moviesWatchlist->removeElement($moviesWatchlist);

        return $this;
    }

    public function isInMoviesWatchlist(Movie $movie): bool
    {
        if ($this->moviesWatchlist->contains($movie)) {
            return true;
        }
        return false;
    }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    /**
     * @return Collection|Series[]
     */
    public function getFavoriteSeries(): Collection
    {
        return $this->favoriteSeries;
    }

    public function addFavoriteSeries(Series $favoriteSeries): self
    {
        if (!$this->favoriteSeries->contains($favoriteSeries)) {
            $this->favoriteSeries[] = $favoriteSeries;
        }

        return $this;
    }

    public function removeFavoriteSeries(Series $favoriteSeries): self
    {
        $this->favoriteSeries->removeElement($favoriteSeries);

        return $this;
    }

    public function isInFavoriteSeries(Series $series): bool
    {
        if ($this->favoriteSeries->contains($series)) {
            return true;
        }
        return false;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getFavoriteMovies(): Collection
    {
        return $this->favoriteMovies;
    }

    public function addFavoriteMovie(Movie $favoriteMovie): self
    {
        if (!$this->favoriteMovies->contains($favoriteMovie)) {
            $this->favoriteMovies[] = $favoriteMovie;
        }

        return $this;
    }

    public function removeFavoriteMovie(Movie $favoriteMovie): self
    {
        $this->favoriteMovies->removeElement($favoriteMovie);

        return $this;
    }

    public function isInFavoriteMovies(Movie $movie): bool
    {
        if ($this->favoriteMovies->contains($movie)) {
            return true;
        }
        return false;
    }

    /**
     * @return Collection|Series[]
     */
    public function getSeenSeries(): Collection
    {
        return $this->seenSeries;
    }

    public function addSeenSeries(Series $seenSeries): self
    {
        if (!$this->seenSeries->contains($seenSeries)) {
            $this->seenSeries[] = $seenSeries;
        }

        return $this;
    }

    public function removeSeenSeries(Series $seenSeries): self
    {
        $this->seenSeries->removeElement($seenSeries);

        return $this;
    }

    public function isInSeenSeries(Series $series): bool
    {
        if ($this->seenSeries->contains($series)) {
            return true;
        }
        return false;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getSeenMovies(): Collection
    {
        return $this->seenMovies;
    }

    public function addSeenMovie(Movie $seenMovie): self
    {
        if (!$this->seenMovies->contains($seenMovie)) {
            $this->seenMovies[] = $seenMovie;
        }

        return $this;
    }

    public function removeSeenMovie(Movie $seenMovie): self
    {
        $this->seenMovies->removeElement($seenMovie);

        return $this;
    }

    public function isInSeenMovies(Movie $movie): bool
    {
        if ($this->seenMovies->contains($movie)) {
            return true;
        }
        return false;
    }

}
