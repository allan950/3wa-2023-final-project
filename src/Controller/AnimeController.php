<?php

namespace App\Controller;

use App\Entity\Anime;
use App\Form\AnimeType;
use App\Repository\AnimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/anime')]
class AnimeController extends AbstractController
{
    #[Route('/', name: 'app_admin_anime_index', methods: ['GET'])]
    public function index(AnimeRepository $animeRepository): Response
    {
        return $this->render('back/admin_anime/index.html.twig', [
            'animes' => $animeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_anime_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnimeRepository $animeRepository): Response
    {
        $anime = new Anime();
        $form = $this->createForm(AnimeType::class, $anime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animeRepository->save($anime, true);

            return $this->redirectToRoute('app_admin_anime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/admin_anime/new.html.twig', [
            'anime' => $anime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_anime_show', methods: ['GET'])]
    public function show(Anime $anime): Response
    {
        return $this->render('back/admin_anime/show.html.twig', [
            'anime' => $anime,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_anime_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Anime $anime, AnimeRepository $animeRepository): Response
    {
        $form = $this->createForm(AnimeType::class, $anime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $animeRepository->save($anime, true);

            return $this->redirectToRoute('app_admin_anime_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('back/admin_anime/edit.html.twig', [
            'anime' => $anime,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_anime_delete', methods: ['POST'])]
    public function delete(Request $request, Anime $anime, AnimeRepository $animeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$anime->getId(), $request->request->get('_token'))) {
            $animeRepository->remove($anime, true);
        }

        return $this->redirectToRoute('app_admin_anime_index', [], Response::HTTP_SEE_OTHER);
    }
}
