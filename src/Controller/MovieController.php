<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Form\CommentType;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use App\Service\CallApiService;
use App\Service\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Finder\Exception\AccessDeniedException;


/**
 * @Route("/movies")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/", name="movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $movies = $movieRepository->findAll();

        $movies = $paginator->paginate(
            $movies,
            $request->query->getInt('page', 1), 
            6 //limit
        );

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="movie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Slugify $slugify, CallApiService $callApiService): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($movie->getTitle());
            $movie->setSlug($slug);
            $entityManager->persist($movie);

            $movieAPI = $callApiService->getMovieData($movie);
            $movie->setPoster($movieAPI['poster_path']);
            $movie->setYear(substr($movieAPI['release_date'], 0, 4));
            $movie->setSynopsis($movieAPI['overview']);
            $movie->setBackground($movieAPI['backdrop_path']);
            $movie->setPopularity($movieAPI['vote_average'] * 10);

            $entityManager->persist($movie);
            $entityManager->flush();

            return $this->redirectToRoute('movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="movie_show", methods={"GET", "POST"})
     */
    public function show(Movie $movie, Request $request, EntityManagerInterface $entityManager, CallApiService $callApiService): Response
    {

        $movieAPI = $callApiService->getMovieData($movie);

        //dd($movieAPI);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setMovie($movie);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('movie_show', ['slug' => $movie->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
            'actors' =>$movie->getActors(),
            'comment' => $comment,
            'form' => $form->createView(),
            'comments' => $movie->getComments(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{slug}/edit", name="movie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/seen", name="movie_seen", methods={"GET","POST"})
     */
    public function addToSeenMovies(Movie $movieId)
    {
        $movie = $this->getDoctrine()
            ->getRepository(Movie::class)
            ->findOneBy(['id' => $movieId]);
        
        if ($this->getUser()->isInSeenMovies($movie)) {
            $this->getUser()->removeSeenMovie($movie);
        } else {
            $this->getUser()->addSeenMovie($movie);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($movie);
        $entityManager->flush();

        // AJAX
        return $this->json([
            'isInSeenMovies' => $this->getUser()->isInSeenMovies($movie)
        ]);
           
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/watchlist", name="movie_watchlist", methods={"GET","POST"})
     */
    public function addToMoviesWatchlist(Movie $movieId)
    {
        $movie = $this->getDoctrine()
            ->getRepository(Movie::class)
            ->findOneBy(['id' => $movieId]);
        
        if ($this->getUser()->isInMoviesWatchlist($movie)) {
            $this->getUser()->removeMoviesWatchlist($movie);
        } else {
            $this->getUser()->addMoviesWatchlist($movie);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($movie);
        $entityManager->flush();

        // AJAX
        return $this->json([
            'isInMoviesWatchlist' => $this->getUser()->isInMoviesWatchlist($movie)
        ]);
           
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/favorite", name="movie_favorite", methods={"GET","POST"})
     */
    public function addToFavoriteMovies(Movie $movieId)
    {
        $movie = $this->getDoctrine()
            ->getRepository(Movie::class)
            ->findOneBy(['id' => $movieId]);
        
        if ($this->getUser()->isInFavoriteMovies($movie)) {
            $this->getUser()->removeFavoriteMovie($movie);
        } else {
            $this->getUser()->addFavoriteMovie($movie);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($movie);
        $entityManager->flush();

        // AJAX
        return $this->json([
            'isInFavoriteMovies' => $this->getUser()->isInFavoriteMovies($movie)
        ]);
           
    }


    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}", name="movie_delete", methods={"POST"})
     */
    public function delete(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($movie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('movie_index', [], Response::HTTP_SEE_OTHER);
    }
}
