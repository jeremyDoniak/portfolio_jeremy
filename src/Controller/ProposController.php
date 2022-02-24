<?php

namespace App\Controller;

use App\Entity\Propos;
use App\Form\ProposType;
use App\Repository\ProposRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/propos')]
class ProposController extends AbstractController
{
    #[Route('/', name: 'propos_index', methods: ['GET'])]
    public function index(ProposRepository $proposRepository): Response
    {
        return $this->render('propos/index.html.twig', [
            'propos' => $proposRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'propos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $propo = new Propos();
        $form = $this->createForm(ProposType::class, $propo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($propo);
            $entityManager->flush();
            $this->addFlash('success', 'Le propo a bien été ajouté');

            return $this->redirectToRoute('propos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('propos/new.html.twig', [
            'propo' => $propo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'propos_show', methods: ['GET'])]
    public function show(Propos $propo): Response
    {
        return $this->render('propos/show.html.twig', [
            'propo' => $propo,
        ]);
    }

    #[Route('/{id}/edit', name: 'propos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Propos $propo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProposType::class, $propo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le propo a bien été modifié');

            return $this->redirectToRoute('propos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('propos/edit.html.twig', [
            'propo' => $propo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'propos_delete', methods: ['POST'])]
    public function delete(Request $request, Propos $propo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$propo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($propo);
            $entityManager->flush();
            $this->addFlash('success', 'Le propo a bien été supprimé');
        }

        return $this->redirectToRoute('propos_index', [], Response::HTTP_SEE_OTHER);
    }
}
