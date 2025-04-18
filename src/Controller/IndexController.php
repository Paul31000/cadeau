<?php

namespace App\Controller;

use App\Repository\GroupeCadeauRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_AUTHENTIFIE')]
final class IndexController extends AbstractController
{
    #[Route('/',name: 'accueil', methods: ['GET'])]
    public function accueil(): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_AUTHENTICATED');
        return $this->render('accueil.html.twig', [
        ]);
    } 

    public function menu(GroupeCadeauRepository $gcRep): Response
    {
        
        $groupesCadeaux=$gcRep->getGroupeCadeauxLies($this->getUser()->getId());

        return $this->render('menu.html.twig', [
            'groupesCadeaux'=>$groupesCadeaux,
        ]);
    }
    
     
}
