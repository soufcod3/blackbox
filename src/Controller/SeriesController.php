<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Series;
use App\Form\CommentType;
use App\Form\SeriesType;
use App\Repository\SeriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * @Route("/series")
 */
class SeriesController extends AbstractController
{
    /**
     * @Route("/", name="series_index", methods={"GET"})
     */
    public function index(SeriesRepository $seriesRepository): Response
    {
        return $this->render('series/index.html.twig', [
            'series' => $seriesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="series_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $series = new Series();
        $form = $this->createForm(SeriesType::class, $series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($series);
            $entityManager->flush();

            return $this->redirectToRoute('series_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('series/new.html.twig', [
            'series' => $series,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="series_show", methods={"GET", "POST"})
     */
    public function show(Series $series, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setSeries($series);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('series_show', ['id' => $series->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('series/show.html.twig', [
            'series' => $series,
            'actors' =>$series->getActors(),
            'comment' => $comment,
            'form' => $form->createView(),
            'comments' => $series->getComments(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="series_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Series $series, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SeriesType::class, $series);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('series_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('series/edit.html.twig', [
            'series' => $series,
            'form' => $form,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/watchlist", name="series_watchlist", methods={"GET","POST"})
     */
    public function addToSeriesWatchlist(Series $seriesId)
    {
        $series = $this->getDoctrine()
            ->getRepository(Series::class)
            ->findOneBy(['id' => $seriesId]);
        
        if ($this->getUser()->isInSeriesWatchlist($series)) {
            $this->getUser()->removeSeriesWatchlist($series);
        } else {
            $this->getUser()->addSeriesWatchlist($series);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($series);
        $entityManager->flush();

        // AJAX
        return $this->json([
            'isInSeriesWatchlist' => $this->getUser()->isInSeriesWatchlist($series)
        ]);
           
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/favorite", name="series_favorite", methods={"GET","POST"})
     */
    public function addToFavoriteSeries(Series $seriesId)
    {
        $series = $this->getDoctrine()
            ->getRepository(Series::class)
            ->findOneBy(['id' => $seriesId]);
        
        if ($this->getUser()->isInFavoriteSeries($series)) {
            $this->getUser()->removeFavoriteSeries($series);
        } else {
            $this->getUser()->addFavoriteSeries($series);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($series);
        $entityManager->flush();

        // AJAX
        return $this->json([
            'isInFavoriteSeries' => $this->getUser()->isInFavoriteSeries($series)
        ]);
           
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}/seen", name="series_seen", methods={"GET","POST"})
     */
    public function addToSeenSeries(Series $seriesId)
    {
        $series = $this->getDoctrine()
            ->getRepository(Series::class)
            ->findOneBy(['id' => $seriesId]);
        
        if ($this->getUser()->isInSeenSeries($series)) {
            $this->getUser()->removeSeenSeries($series);
        } else {
            $this->getUser()->addSeenSeries($series);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($series);
        $entityManager->flush();

        // AJAX
        return $this->json([
            'isInSeenSeries' => $this->getUser()->isInSeenSeries($series)
        ]);
           
    }

    /**
     * @Route("/{id}", name="series_delete", methods={"POST"})
     */
    public function delete(Request $request, Series $series, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$series->getId(), $request->request->get('_token'))) {
            $entityManager->remove($series);
            $entityManager->flush();
        }

        return $this->redirectToRoute('series_index', [], Response::HTTP_SEE_OTHER);
    }
}
