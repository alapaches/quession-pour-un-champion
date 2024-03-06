<?php

namespace App\Controller;

use App\Entity\Jeux;
use App\Entity\Equipe;
use App\Form\JeuxType;
use App\Entity\Question;
use App\Entity\Proposition;
use App\Entity\Score;
use App\Entity\ScoreEquipe;
use App\Entity\Theme;
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
        $themes = $this->em->getRepository(Theme::class)->findAll();
        $questions = $this->em->getRepository(Question::class)->findBy(array("jeu" => $jeu));


        return $this->render('jeux/running.html.twig', [
            'jeu' => $jeu,
            'questions' => $questions,
            'equipes' => $equipes,
            'themes' => $themes,
        ]);
    }

    #[Route('/{id}/check', name: 'app_jeux_check', methods: ['GET', 'POST'])]
    public function check(Request $request): Response
    {
        $idJeu = intval($request->get("id"));
        $idTheme = intval($request->get("idTheme"));
        $tmpTabDifficulte = [];
        if ($idTheme) {
            $tabQuestions = $this->em->getRepository(Question::class)->findBy(["theme" => $idTheme]);
            foreach ($tabQuestions as $question) {
                if($idTheme === 11) {
                    $difficulte = "Mystère";
                } else {
                    $difficulte = $question->getDifficulte() === "1" ? "Facile" : "Difficile";
                }
                array_push($tmpTabDifficulte, $difficulte);
            }
            $tabDifficulte = array_unique($tmpTabDifficulte);
            // dump($tabQuestions);
            // dump($tmpTabDifficulte);
            // dump($tabDifficulte);
            // die();
            return $this->json([
                "questions" => $tabQuestions,
                "difficulte" => $tabDifficulte
            ], 200, [], ['groups' => 'jeu']);
        } else {
            $equipeParam = intval($request->request->get("equipe"));
            $propositionParam = intval($request->request->get("proposition"));
            $proposition = $this->em->getRepository(Proposition::class)->findOneBy(array('id' => $propositionParam));
            $reponse = $proposition->isValide();
            $equipe = $this->em->getRepository(Equipe::class)->findOneById($equipeParam);
            $jeuCourant = $this->em->getRepository(Jeux::class)->findOneById($idJeu);
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
            $updatedScore = $reponse === true ? $scoreTmp + 1 : $scoreTmp;
            $scoreEquipe->setScore($updatedScore);

            $this->em->flush();

            return $this->json([
                'equipe' => $equipeParam,
                'validation' => $reponse,
                'jeu' => $idJeu,
                'scoreEquipe' => $scoreEquipe->getScore()
            ]);
        }
    }

    #[Route('/{id}', name: 'app_jeux_delete', methods: ['POST'])]
    public function delete(Request $request, Jeux $jeux, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $jeux->getId(), $request->request->get('_token'))) {
            $entityManager->remove($jeux);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_jeux_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/reponse/{id}/get', name: 'app_jeux_get_reponse', methods: ['GET'])]
    public function getReponse(Request $request): Response
    {
        $idQuestion = intval($request->get("idQuestion"));
        $reponse = $this->em->getRepository(Proposition::class)->findOneBy(["question" => $idQuestion])->getTitre();

        return $this->json([
            "reponseQuestion" => $reponse
        ], 200, [], ['groups' => 'jeu']);
    }

    #[Route('/difficulte/{difficulte}/get', name: 'app_jeux_get_questions_by_difficulty', methods: ['GET'])]
    public function getQuestionsByDifficulty(Request $request): Response
    {
        $difficulte = intval($request->get("difficulte"));
        $theme = intval($request->query->get("theme"));
        $titreTheme = $this->em->getRepository(Theme::class)->findOneById($theme)->getNom();
        $question = [];
        $questionsEntity = $this->em->getRepository(Question::class)->findOneBy(["theme" => $theme, "difficulte" => $difficulte]);
        $questionsTab = $questionsEntity->getPropositions()->toArray()[0];
        $question["intitule"] = $questionsEntity->getIntitule();
        $question["theme"] = $titreTheme;
        $question["reponseValide"] = $questionsTab->getTitre();

        return $this->json([
            "question" => $question
        ], 200, [], ['groups' => 'jeu']);
    }

    #[Route('/score/{jeu}/get', name: 'app_jeux_get_score', methods: ['GET'])]
    public function getScore(Request $request): Response
    {
        $idJeu = intval($request->get("jeu"));
        $tabScoreEquipe = [];
        $equipes = $this->em->getRepository(Equipe::class)->findAll();
        foreach ($equipes as $equipe) {
            $sTab = [];
            $tmpScore = 0;
            $scoreEquipe = $this->em->getRepository(ScoreEquipe::class)->findOneBy(["jeu" => $idJeu, "equipe" => $equipe->getId()]);
            if($scoreEquipe) {
                $tmpScore = $tmpScore + $scoreEquipe->getScore();
            }
            $finalScore = round($tmpScore);
            $sTab["equipe"] = $equipe->getId();
            $sTab["score"] = $finalScore;

            array_push($tabScoreEquipe, $sTab);
        }
        

        return $this->json(['tableauScores' => $tabScoreEquipe]);
    }

    #[Route('/score/{jeu}/set', name: 'app_jeux_set_score', methods: ['GET', 'POST'])]
    public function setScore(Request $request): Response
    {
        $idJeu = intval($request->get("jeu"));
        $equipeParam = intval($request->get("equipe"));
        $score = intval($request->get("score"));

        $equipe = $this->em->getRepository(Equipe::class)->findOneById($equipeParam);
        $jeuCourant = $this->em->getRepository(Jeux::class)->findOneById($idJeu);
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
        $updatedScore = $scoreTmp + $score;
        $scoreEquipe->setScore($updatedScore);

        $this->em->flush();

        return $this->json(['score' => $scoreEquipe->getScore(), 'equipe' => $equipeParam], 200, [], ['groups' => 'jeu']);
    }
}
