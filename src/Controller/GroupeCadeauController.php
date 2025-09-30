<?php

namespace App\Controller;

use App\Entity\GroupeCadeau;
use App\Form\GroupeCadeauType;
use App\Repository\GroupeCadeauRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_AUTHENTIFIE')]
#[Route('/groupe/cadeau')]
final class GroupeCadeauController extends AbstractController
{
    #[Route(name: 'app_groupe_cadeau_index', methods: ['GET'])]
    public function index(GroupeCadeauRepository $groupeCadeauRepository): Response
    {
        $groupeCadeau= $groupeCadeauRepository->getGroupeCadeauxLies($this->getUser()->getId());
        return $this->render('groupe_cadeau/index.html.twig', [
            'groupe_cadeaus' => $groupeCadeau,
        ]);
    }

    #[Route('/new', name: 'app_groupe_cadeau_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $groupeCadeau = new GroupeCadeau();
        $groupeCadeau->setDateCreation(new DateTime());
        
        $form = $this->createForm(GroupeCadeauType::class, $groupeCadeau,['users'=>$this->getUser()->getAmi()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $groupeCadeau->setTypeGroupe("GroupeNoel");
            $groupeCadeau->addUtilisateursConcerne($this->getUser());
            foreach($groupeCadeau->getutilisateurConcernes() as $util){
                foreach($groupeCadeau->getutilisateurConcernes() as $util2){
                    $util->addAmi($util2);
                    $util2->addAmi($util);
                    $entityManager->persist($util2);
                    $entityManager->persist($util);        
                }
            }

            $entityManager->persist($groupeCadeau);
            $entityManager->flush();

            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('groupe_cadeau/new.html.twig', [
            'groupe_cadeau' => $groupeCadeau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_groupe_cadeau_show', methods: ['GET'])]
    public function show(GroupeCadeau $groupeCadeau): Response
    {
        return $this->render('groupe_cadeau/show.html.twig', [
            'groupe_cadeau' => $groupeCadeau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_groupe_cadeau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GroupeCadeau $groupeCadeau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupeCadeauType::class, $groupeCadeau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_groupe_cadeau_show', ["id"=>$groupeCadeau->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('groupe_cadeau/edit.html.twig', [
            'groupe_cadeau' => $groupeCadeau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_groupe_cadeau_delete', methods: ['POST'])]
    public function delete(Request $request, GroupeCadeau $groupeCadeau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$groupeCadeau->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($groupeCadeau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_groupe_cadeau_index', [], Response::HTTP_SEE_OTHER);
    }
}
