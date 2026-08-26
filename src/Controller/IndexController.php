<?php

namespace App\Controller;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GroupeCadeauRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


final class IndexController extends AbstractController
{
    #[Route('/accueil2',name: 'accueil', methods: ['GET'])]
    #[IsGranted('ROLE_AUTHENTIFIE')]
    public function accueil(EntityManagerInterface $em): Response
    {
        $this->getUser()->setDateConnexion(new DateTime());
        
        $em->flush();
        return $this->render('accueil.html.twig', [
        ]);
    } 

    #[Route('/description',name: 'presentation', methods: ['GET'])]
    public function presentation(): Response
    {
        
        return $this->render('description.html.twig', [
        ]);
    } 

    #[IsGranted('ROLE_AUTHENTIFIE')]
    public function menu(GroupeCadeauRepository $gcRep): Response
    {
        
        $groupesCadeaux=$gcRep->getGroupeCadeauxLies($this->getUser()->getId());

        return $this->render('menu.html.twig', [
            'groupesCadeaux'=>$groupesCadeaux,
        ]);
    }
    
     
}
