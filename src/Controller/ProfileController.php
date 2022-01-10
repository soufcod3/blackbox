<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;


/**
 * @IsGranted("ROLE_USER")
 * @Route("/profile/", name="profile_")
 */
class ProfileController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        $user = $this->security->getUser();

        /** @var User $user */;
        $seriesWatchlist = $user->getSeriesWatchlist();
        $moviesWatchlist = $user->getMoviesWatchlist();
        $seriesFavorite = $user->getFavoriteSeries();
        $moviesFavorite = $user->getFavoriteMovies();
        $seenSeries = $user->getSeenSeries();
        $seenMovies = $user->getSeenMovies();

        return $this->render(
            'profile/profile.html.twig',
            ['user' => $user,
            'seriesWatchlist' => $seriesWatchlist,
            'moviesWatchlist' => $moviesWatchlist,
            'seriesFavorite' => $seriesFavorite,
            'moviesFavorite' => $moviesFavorite,
            'seenSeries' => $seenSeries,
            'seenMovies' => $seenMovies,
            ]
        );
    }

    /**
     * @Route("series-watchlist", name="series_watchlist")
     */
    public function allSeriesWatchlist()
    {
        $user = $this->security->getUser();

        /** @var User $user */;
        $series = $user->getSeriesWatchlist();


        return $this->render(
            'profile/series-watchlist.html.twig',
            ['series' => $series]
        );
    }

    /**
     * @Route("movies-watchlist", name="movies_watchlist")
     */
    public function allMoviesWatchlist()
    {
        $user = $this->security->getUser();

        /** @var User $user */;
        $movies = $user->getMoviesWatchlist();

        return $this->render(
            'profile/movies-watchlist.html.twig',
            ['movies' => $movies]
        );
    }

    /**
     * @Route("series-favorite", name="series_favorite")
     */
    public function allSeriesFavorite()
    {
        $user = $this->security->getUser();

        /** @var User $user */;
        $series = $user->getFavoriteSeries();

        return $this->render(
            'profile/series-favorite.html.twig',
            ['series' => $series]
        );
    }

    /**
     * @Route("movies-favorite", name="favorite")
     */
    public function allMoviesFavorite()
    {
        $user = $this->security->getUser();

        /** @var User $user */;
        $movies = $user->getFavoriteMovies();

        return $this->render(
            'profile/movies-favorite.html.twig',
            ['movies' => $movies]
        );
    }

        /**
     * @Route("seen-series", name="series_seen")
     */
    public function allSeenSeries()
    {
        $user = $this->security->getUser();

        /** @var User $user */;
        $series = $user->getSeenSeries();

        return $this->render(
            'profile/seen-series.html.twig',
            ['series' => $series]
        );
    }

    /**
     * @Route("seen-movies", name="movies_seen")
     */
    public function allSeenMovies()
    {
        $user = $this->security->getUser();

        /** @var User $user */;
        $movies = $user->getSeenMovies();

        return $this->render(
            'profile/seen-movies.html.twig',
            ['movies' => $movies]
        );
    }
}
