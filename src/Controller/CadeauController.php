<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Cadeau;
use App\Form\CadeauType;
use App\Entity\ListeCadeau;
use App\Entity\UtilisateurOffre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_AUTHENTIFIE')]
#[Route('/cadeau')]
final class CadeauController extends AbstractController
{

    #[Route('/{listeCadeaux}/new', name: 'app_cadeau_new', methods: ['GET', 'POST'])]
    public function new(ListeCadeau $listeCadeaux, Request $request, EntityManagerInterface $entityManager): Response
    {
        $cadeau = new Cadeau();
        $listeCadeaux->addCadeaux($cadeau);
        $form = $this->createForm(CadeauType::class, $cadeau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cadeau->setDate(new DateTime());
            $entityManager->persist($cadeau);
            $entityManager->flush();

            return $this->redirectToRoute('app_cadeau_new', ['listeCadeaux'=>$listeCadeaux->getId()], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('cadeau/new.html.twig', [
            'listeCadeaux'=>$listeCadeaux,
            'cadeau' => $cadeau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_cadeau_show', methods: ['GET'])]
    public function show(Cadeau $cadeau): Response
    {
        $estDemandeur=$cadeau->getDestinataireCadeau()==$this->getUser()?true:false;
        return $this->render('cadeau/show.html.twig', [
            'estDemandeur'=>$estDemandeur,
            'cadeau' => $cadeau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cadeau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cadeau $cadeau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CadeauType::class, $cadeau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cadeau_new',
                ['listeCadeaux' => $listeCadeauOrigine->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render('cadeau/edit.html.twig', [
            'cadeau' => $cadeau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cadeau_delete', methods: ['POST'])]
    public function delete(Request $request, Cadeau $cadeau, EntityManagerInterface $entityManager): Response
    {
        $listeCadeauOrigine= $cadeau->getListeCadeau();

        if ($this->isCsrfTokenValid('delete'.$cadeau->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cadeau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cadeau_new', ['listeCadeaux'=>$listeCadeauOrigine->getId()], Response::HTTP_SEE_OTHER);
    } 

    //TODO SI JE RECLIQUE SUR OFFRE JE REMPLACE
    #[Route('/{id}/jeprends', name: 'app_cadeau_je_prends', methods: ['POST'])]
    public function jePrends(Request $request, Cadeau $cadeau, EntityManagerInterface $entityManager): Response
    {
        $form = $this
                        ->createFormBuilder()
                        ->setAction($this->generateUrl('app_cadeau_je_prends',['id'=>$cadeau->getId()]))
                        ->add('a_hauteur_de',NumberType::class,['mapped'=>false])
                        ->add('je_participe',SubmitType::class)
                        ->add('j_offre',SubmitType::class)
                        ->getForm();
        
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {

            if(isset($request->get('form')["je_participe"])&&!isset($request->get('form')["j_offre"])){
                $responsable=false;
            }else{
                $responsable=true;
            }
            
            $utilisateurOffre=$this->returnUserOffre($cadeau,$this->getUser());
            
            if($utilisateurOffre==null){
                $utilisateurOffre=new UtilisateurOffre;
                $utilisateurOffre->setUtilisateurConcerne($this->getUser());
                $cadeau->addUtilisateurOffre($utilisateurOffre);
                $utilisateurOffre->setAchete($responsable);
            }
            
            $utilisateurOffre->setMontant($request->get('form')["a_hauteur_de"]);

            $entityManager->persist($utilisateurOffre);
            $entityManager->persist($cadeau);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_cadeau_show', ['id'=>$cadeau->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('cadeau/cadeauOffre.html.twig', [
            'cadeau' => $cadeau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/jairegarde', name: 'app_cadeau_jai_regarde', methods: ['POST'])]
    public function jairegarde(Request $request, Cadeau $cadeau, EntityManagerInterface $entityManager): Response
    {
        $form = $this
                    ->createFormBuilder()
                    ->setAction($this->generateUrl('app_cadeau_jai_regarde',['id'=>$cadeau->getId()]))
                    ->add('Prix_du_cadeau',NumberType::class,['mapped'=>false])
                    ->getForm();

        $form->handleRequest($request);
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $cadeau->setPrix($request->get('form')["Prix_du_cadeau"]);
            $entityManager->persist($cadeau);
            $entityManager->flush();
            
            return $this->redirectToRoute('app_cadeau_show', ['id'=>$cadeau->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cadeau/jaiRegarde.html.twig', [
            'cadeau' => $cadeau,
            'form' => $form,
        ]);
    }

    public function returnUserOffre(Cadeau $cadeau, User $user):?UtilisateurOffre{
        foreach($cadeau->getUtilisateurOffres() as $utilisateurOffre){
            if($utilisateurOffre->getUtilisateurConcerne()==$user){
                return $utilisateurOffre;
            }
        }
        return null;
    }
}
