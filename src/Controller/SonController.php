<?php

namespace App\Controller;

use App\Entity\Son;
use App\Entity\Jeux;
use App\Form\SonType;
use App\Entity\Equipe;
use App\Entity\ScoreEquipe;
use App\Repository\SonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/son')]
class SonController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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

    #[Route('/{idSon}/check/{idJeu}', name: 'app_son_check', methods: ['GET','POST'])]
    public function checkSonReponse(Request $request): Response
    {
        $idSon = intval($request->get("idSon"));
        $idJeu = intval($request->get("idJeu"));
        $typeRep = json_decode($request->get("typeRep"));
        $equipeParam = intval($request->get("equipe"));
        $data = [];
        $score = 0;
        $son = $this->em->getRepository(Son::class)->findOneById($idSon);
        
        if($typeRep === true) {
            $points = $son->getPoints();
            $jeuCourant = $this->em->getRepository(Jeux::class)->findOneById($idJeu);
            $equipe = $this->em->getRepository(Equipe::class)->findOneById($equipeParam);
            $scoreEquipe = $this->em->getRepository(ScoreEquipe::class)->findOneBy([
                "jeu" => $jeuCourant,
                "equipe" => $equipe
            ]);
            if (!$scoreEquipe) {
                $scoreEquipe = new ScoreEquipe();
                $scoreEquipe->setJeu($jeuCourant);
                $scoreEquipe->setEquipe($equipe);
                $scoreEquipe->setScore(0);
                $this->em->persist($scoreEquipe);
            }
            $scoreTmp = $scoreEquipe->getScore();
            $updatedScore = $scoreTmp += $points;
            $scoreEquipe->setScore($updatedScore);
            $son->setLock(true);
            $this->em->flush();

            $data = [
                "typeRep" => $typeRep,
                "score" => $score
            ];
        }

        return $this->json($data);
    }
}
