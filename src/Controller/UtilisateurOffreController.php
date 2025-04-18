<?php

namespace App\Controller;

use App\Entity\Cadeau;
use App\Entity\UtilisateurOffre;
use App\Form\UtilisateurOffreType;
use App\Repository\UtilisateurOffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_AUTHENTIFIE')]
#[Route('/utilisateur/offre')]
final class UtilisateurOffreController extends AbstractController
{
    /* #[Route(name: 'app_utilisateur_offre_index', methods: ['GET'])]
    public function index(UtilisateurOffreRepository $utilisateurOffreRepository): Response
    {
        return $this->render('utilisateur_offre/index.html.twig', [
            'utilisateur_offres' => $utilisateurOffreRepository->findAll(),
        ]);
    }

    #[Route('/[cadeau]/new', name: 'app_utilisateur_offre_new', methods: ['GET', 'POST'])]
    public function new(Cadeau $cadeau, Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateurOffre = new UtilisateurOffre();
        $form = $this->createForm(UtilisateurOffreType::class, $utilisateurOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateurOffre->setCadeau($cadeau);
            $utilisateurOffre->setUtilisateurConcerne($this->getUser());
            $entityManager->persist($utilisateurOffre);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur_offre/new.html.twig', [
            'utilisateur_offre' => $utilisateurOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_offre_show', methods: ['GET'])]
    public function show(UtilisateurOffre $utilisateurOffre): Response
    {
        return $this->render('utilisateur_offre/show.html.twig', [
            'utilisateur_offre' => $utilisateurOffre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UtilisateurOffre $utilisateurOffre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurOffreType::class, $utilisateurOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur_offre/edit.html.twig', [
            'utilisateur_offre' => $utilisateurOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_offre_delete', methods: ['POST'])]
    public function delete(Request $request, UtilisateurOffre $utilisateurOffre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateurOffre->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateurOffre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_offre_index', [], Response::HTTP_SEE_OTHER);
    } */
}
