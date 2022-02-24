<?php

namespace App\Controller;

use App\Entity\Projets;
use App\Form\ProjetsType;
use App\Repository\ProjetsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/projets')]
class ProjetsController extends AbstractController
{
    #[Route('/', name: 'projets_index', methods: ['GET'])]
    public function index(ProjetsRepository $projetsRepository): Response
    {
        return $this->render('projets/index.html.twig', [
            'projets' => $projetsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'projets_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry): Response
    {
        $projet = new Projets();
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoImg = $form['img']->getData();
            $extensionImg = $infoImg->guessExtension();
            $nomImg = time() . $extensionImg;
            $infoImg->move($this->getParameter('dossier_photos_projets'), $nomImg);
            $projet->setImg($nomImg);
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($projet);
            $entityManager->flush();
            $this->addFlash('success', 'Le projet a bien été ajouté');

            return $this->redirectToRoute('projets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projets/new.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'projets_show', methods: ['GET'])]
    public function show(Projets $projet): Response
    {
        return $this->render('projets/show.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/{id}/edit', name: 'projets_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projets $projet, EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoImg = $form['img']->getData();
            $nomOldImg = $projet->getImg();
            if ($infoImg !== null) {
                $cheminOldImg = $this->getParameter('dossier_photos_projets') . '/' . $nomOldImg;
                if (file_exists($cheminOldImg)) {
                    unlink($cheminOldImg);
                }
                $extensionImg = $infoImg->guessExtension();
                $nomImg = time() . $extensionImg;
                $infoImg->move($this->getParameter('dossier_photos_projets'), $nomImg);
                $projet->setImg($nomImg);
            } else {
                $projet->setImg($nomOldImg);
            }
            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($projet);
            $entityManager->flush();
            $this->addFlash('success', 'Le projet a bien été modifié');

            return $this->redirectToRoute('projets_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projets/edit.html.twig', [
            'projet' => $projet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'projets_delete', methods: ['POST'])]
    public function delete(Request $request, Projets $projet, EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $nomImg = $projet->getImg();
            if ($nomImg !== null) {
                $cheminImg = $this->getParameter('dossier_photos_projets') . '/' . $nomImg;
                if (file_exists($cheminImg)) {
                    unlink($cheminImg);
                }
            }
            $entityManager = $managerRegistry->getManager();
            $entityManager->remove($projet);
            $entityManager->flush();
            $this->addFlash('success', 'Le projet a bien été supprimé');
        }

        return $this->redirectToRoute('projets_index', [], Response::HTTP_SEE_OTHER);
    }
}
