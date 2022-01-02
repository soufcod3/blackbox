<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Movie;
use App\Form\CommentType;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="movie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/{id}", name="movie_show", methods={"GET", "POST"})
     */
    public function show(Movie $movie, Request $request, EntityManagerInterface $entityManager): Response
    {

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setMovie($movie);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()], Response::HTTP_SEE_OTHER);
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
     * @Route("/{id}/edit", name="movie_edit", methods={"GET", "POST"})
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
