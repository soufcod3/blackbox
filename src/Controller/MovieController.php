<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Comment;
use App\Entity\Movie;
use App\Form\CommentType;
use App\Form\MovieType;
use App\Form\SearchType;
use App\Repository\CommentRepository;
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
        $data = new SearchData();
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        $movies = $movieRepository->findSearch($data);

        $movies = $paginator->paginate(
            $movies,
            $request->query->getInt('page', 1),
            12 //limit
        );

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
            'form' => $form->createView(),
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
            'actors' => $movie->getActors(),
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
    public function addToSeenMovies(Movie $movieId, EntityManagerInterface $em)
    {
        $movie = $em->getRepository(Movie::class)
            ->findOneBy(['id' => $movieId]);

        /** @var User $user */
        $user = $this->getUser();

        if ($user->isInSeenMovies($movie)) {
            $user->removeSeenMovie($movie);
        } else {
            $user->addSeenMovie($movie);
        }

        $em->persist($movie);
        $em->flush();

        // AJAX
        return $this->json([
            'isInSeenMovies' => $user->isInSeenMovies($movie)
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

        /** @var User $user */
        $user = $this->getUser();

        if ($user->isInMoviesWatchlist($movie)) {
            $user->removeMoviesWatchlist($movie);
        } else {
            $user->addMoviesWatchlist($movie);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($movie);
        $entityManager->flush();

        // AJAX
        return $this->json([
            'isInMoviesWatchlist' => $user->isInMoviesWatchlist($movie)
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

        /** @var User $user */
        $user = $this->getUser();

        if ($user->isInFavoriteMovies($movie)) {
            $user->removeFavoriteMovie($movie);
        } else {
            $user->addFavoriteMovie($movie);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($movie);
        $entityManager->flush();

        // AJAX
        return $this->json([
            'isInFavoriteMovies' => $user->isInFavoriteMovies($movie)
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/delete", name="movie_delete", methods={"POST"})
     */
    public function delete(Request $request, Movie $movie, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy(['movie' => $movie->getId()]);
        
        if ($this->isCsrfTokenValid('delete' . $movie->getId(), $request->request->get('_token'))) {
            foreach ($comments as $comment) {
                $entityManager->remove($comment);
            }
            $entityManager->remove($movie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('movie_index', [], Response::HTTP_SEE_OTHER);
    }
}
