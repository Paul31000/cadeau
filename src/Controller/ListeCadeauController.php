<?php

namespace App\Controller;

use App\Entity\GroupeCadeau;
use App\Entity\ListeCadeau;
use App\Repository\ListeCadeauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_AUTHENTIFIE')]
#[Route('/liste/cadeau')]
final class ListeCadeauController extends AbstractController
{
/*     #[Route(name: 'app_liste_cadeau_index', methods: ['GET'])]
    public function index(ListeCadeauRepository $listeCadeauRepository): Response
    {
        return $this->render('liste_cadeau/index.html.twig', [
            'liste_cadeaus' => $listeCadeauRepository->findAll(),
        ]);
    } */

    #[Route('/new/{id}', name: 'app_liste_cadeau_new', methods: ['GET', 'POST'])]
    public function new(GroupeCadeau $groupeCadeau, ListeCadeauRepository $listeCadeauRep, EntityManagerInterface $entityManager): Response
    {
        /* NE PAS RECREER LA LISTE SI ELLE EXISTE DEJA */
        $listeCadeau=$listeCadeauRep->findListForCurrentUser($this->getUser(), $groupeCadeau->getId());
        
        if(!$listeCadeau){
            $listeCadeau = new ListeCadeau();

            $listeCadeau->setGroupeCadeau($groupeCadeau);
            $listeCadeau->setUtilisateur($this->getUser());
            $entityManager->persist($listeCadeau);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_cadeau_new', ['listeCadeaux'=>$listeCadeau->getId()], Response::HTTP_SEE_OTHER);
    }

   /*  #[Route('/{id}', name: 'app_liste_cadeau_show', methods: ['GET'])]
    public function show(ListeCadeau $listeCadeau): Response
    {
        return $this->render('liste_cadeau/show.html.twig', [
            'liste_cadeau' => $listeCadeau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_liste_cadeau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ListeCadeau $listeCadeau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ListeCadeauType::class, $listeCadeau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_cadeau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liste_cadeau/edit.html.twig', [
            'liste_cadeau' => $listeCadeau,
            'form' => $form,
        ]);
    }

    TODO delete les groupes vieux de 10 mois. (ajout date expiration)

    #[Route('/{id}', name: 'app_liste_cadeau_delete', methods: ['POST'])]
    public function delete(Request $request, ListeCadeau $listeCadeau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$listeCadeau->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($listeCadeau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_liste_cadeau_index', [], Response::HTTP_SEE_OTHER);
    } */
}
