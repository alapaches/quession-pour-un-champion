<?php

namespace App\Controller;

use App\Entity\Son;
use App\Form\SonType;
use App\Repository\SonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/son')]
class SonController extends AbstractController
{
    #[Route('/', name: 'app_son_index', methods: ['GET'])]
    public function index(SonRepository $sonRepository): Response
    {
        return $this->render('son/index.html.twig', [
            'sons' => $sonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_son_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $son = new Son();
        $form = $this->createForm(SonType::class, $son);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($son);
            $entityManager->flush();

            return $this->redirectToRoute('app_son_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('son/new.html.twig', [
            'son' => $son,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_son_show', methods: ['GET'])]
    public function show(Son $son): Response
    {
        return $this->render('son/show.html.twig', [
            'son' => $son,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_son_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Son $son, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SonType::class, $son);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_son_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('son/edit.html.twig', [
            'son' => $son,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_son_delete', methods: ['POST'])]
    public function delete(Request $request, Son $son, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$son->getId(), $request->request->get('_token'))) {
            $entityManager->remove($son);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_son_index', [], Response::HTTP_SEE_OTHER);
    }
}
