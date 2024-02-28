<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Entity\Equipe;
use App\Form\JeuxType;
use App\Entity\Question;
use App\Entity\Proposition;
use App\Entity\Score;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\JeuxRepository;
use App\Service\ScoreService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/jeux')]
class JeuxController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'app_jeux_index', methods: ['GET'])]
    public function index(JeuxRepository $jeuxRepository): Response
    {
        return $this->render('jeux/index.html.twig', [
            'jeux' => $jeuxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_jeux_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jeux = new Jeux();
        $form = $this->createForm(JeuxType::class, $jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jeux);
            $entityManager->flush();

            return $this->redirectToRoute('app_jeux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jeux/new.html.twig', [
            'jeux' => $jeux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jeux_show', methods: ['GET'])]
    public function show(Jeux $jeux): Response
    {
        return $this->render('jeux/show.html.twig', [
            'jeux' => $jeux,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_jeux_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Jeux $jeux, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JeuxType::class, $jeux);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_jeux_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jeux/edit.html.twig', [
            'jeux' => $jeux,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/play', name: 'app_jeux_play', methods: ['GET', 'POST'])]
    public function play(Request $request, Jeux $jeu): Response
    {
        $equipes = $this->em->getRepository(Equipe::class)->findAll();
        $questions = $this->em->getRepository(Question::class)->findBy(array("jeu" => $jeu));
        
        
        return $this->render('jeux/running.html.twig', [
            'jeu' => $jeu,
            'questions' => $questions,
            'equipes' => $equipes,
        ]);
    }

    #[Route('/{id}/check', name: 'app_jeux_check', methods: ['GET', 'POST'])]
    public function check(Request $request): Response
    {   
        $equipeParam = intval($request->request->get("equipe"));
        $scoreService = new ScoreService($this->em);
        $idJeu = intval($request->get("id"));
        $propositionParam = intval($request->request->get("proposition"));
        $proposition = $this->em->getRepository(Proposition::class)->findOneBy(array('id' => $propositionParam));
        $reponse = $proposition->isValide();
        $equipe = $this->em->getRepository(Equipe::class)->findOneById($equipeParam);
        $jeuCourant = $this->em->getRepository(Jeux::class)->findOneById($idJeu);
        $testScore = $scoreService->hasScore($equipe);
        $newScore = new Score();
        if(!$testScore) {
            $newScore->setTotal(0);
            $newScore->addEquipe($equipe);
        } else {
            $scoreTmp = $equipe->getScores()[0]->getTotal();
            $updatedScore = $scoreTmp + 1;
            $equipe->addScore($updatedScore);
            $this->em->persist($equipe);
        }
        $newScore->setJeu($jeuCourant);
        $this->em->persist($newScore);
        $this->em->flush();

        return $this->json([
            'equipe' => $equipeParam,
            'validation' => $reponse,
            'jeu' => $idJeu,
        ]);
    }

    #[Route('/{id}', name: 'app_jeux_delete', methods: ['POST'])]
    public function delete(Request $request, Jeux $jeux, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jeux->getId(), $request->request->get('_token'))) {
            $entityManager->remove($jeux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_jeux_index', [], Response::HTTP_SEE_OTHER);
    }
}
