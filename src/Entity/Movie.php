<?php

namespace App\Entity;

use App\Repository\MovieRepository;
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

}
