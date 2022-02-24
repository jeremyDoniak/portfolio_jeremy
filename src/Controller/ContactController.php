<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Address;
use App\Repository\ProposRepository;
use App\Repository\ProjetsRepository;
use App\Repository\CompetencesRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/', name: 'contact')]
    public function index(Request $request, SluggerInterface $slugger, MailerInterface $mailer, CompetencesRepository $competencesRepository, ProjetsRepository $projetsRepository, ProposRepository $proposRepository): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        $competence = $competencesRepository->findAll();
        $projet = $projetsRepository->findAll();
        $propo = $proposRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            $email = (new TemplatedEmail())
                ->from(new Address($contact['email'], $contact['prenom'] . ' ' . $contact['nom']))
                ->to(new Address('alfred.dorc@gmail.com'))
                ->subject('Portfolio - JD - demande de contact')
                ->htmlTemplate('contact/contact_email.html.twig')
                ->context([
                    'prenom' => $contact['prenom'],
                    'nom' => $contact['nom'],
                    'adresseEmail' => $contact['email'],
                    'message' => $contact['message'],
                ]);
            if ($contact['fichier'] !== null) {
                $originalFilename = pathinfo($contact['fichier']->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '.' . $contact['fichier']->guessExtension();
                $email->attachFromPath($contact['fichier']->getPathName(), $newFilename);
            }
            $mailer->send($email);
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('home/index.html.twig', [
            'contactForm' => $form->createView(),
            'competences' => $competence,
            'projets' => $projet,
            'propos' => $propo,
        ]);
    }
}
