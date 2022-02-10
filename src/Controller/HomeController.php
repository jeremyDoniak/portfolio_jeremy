<?php

namespace App\Controller;

use App\Repository\ProposRepository;
use App\Repository\ProjetsRepository;
use App\Repository\CompetencesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(CompetencesRepository $competencesRepository, ProjetsRepository $projetsRepository, ProposRepository $proposRepository): Response
    {
        $competence = $competencesRepository->findAll();
        $projet = $projetsRepository->findAll();
        $propo = $proposRepository->findAll();

        return $this->render('home/index.html.twig', [
            'competences' => $competence,
            'projets' => $projet,
            'propos' => $propo
        ]);
    }
}
