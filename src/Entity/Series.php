<?php

namespace App\Entity;

use App\Repository\SeriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeriesRepository::class)
 */
class Series
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
     * @ORM\Column(type="integer")
     */
    private $startYear;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $endYear;

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
     * @ORM\Column(type="integer")
     */
    private $myRate;

    /**
     * @ORM\Column(type="integer")
     */
    private $popularity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $myReview;

    /**
     * @ORM\Column(type="integer")
     */
    private $seasonsCount;

    /**
     * @ORM\Column(type="integer")
     */
    private $episodesCount;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="series")
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Actor::class, mappedBy="series")
     */
    private $actors;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $watchedOn;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
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

    public function getStartYear(): ?int
    {
        return $this->startYear;
    }

    public function setStartYear(int $startYear): self
    {
        $this->startYear = $startYear;

        return $this;
    }

    public function getEndYear(): ?int
    {
        return $this->endYear;
    }

    public function setEndYear(?int $endYear): self
    {
        $this->endYear = $endYear;

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

    public function setTrailerLink(?string $trailerLink): self
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

    public function getPopularity(): ?int
    {
        return $this->popularity;
    }

    public function setPopularity(int $popularity): self
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

    public function getSeasonsCount(): ?int
    {
        return $this->seasonsCount;
    }

    public function setSeasonsCount(int $seasonsCount): self
    {
        $this->seasonsCount = $seasonsCount;

        return $this;
    }

    public function getEpisodesCount(): ?int
    {
        return $this->episodesCount;
    }

    public function setEpisodesCount(int $episodesCount): self
    {
        $this->episodesCount = $episodesCount;

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
            $actor->addSeries($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        if ($this->actors->removeElement($actor)) {
            $actor->removeSeries($this);
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

}
