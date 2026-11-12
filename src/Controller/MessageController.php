<?php

namespace App\Controller;

use DateTime;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_AUTHENTIFIE')]
#[Route('/message')]
final class MessageController extends AbstractController
{
    //entitee liee est un string du type lie. ex: cadeau, utilisateur... etc
    //id entitee liee est le numero de l'entite
    #[Route('/{idEntiteeLiee}/{entiteeLiee}/index',name: 'app_message_index', methods: ['GET'])]
    public function index(int $idEntiteeLiee, string $entiteeLiee, MessageRepository $messageRepository): Response
    {
        $messages= $messageRepository->findBy(['idEntiteeLiee'=>$idEntiteeLiee,"entiteeLiee"=>$entiteeLiee]);
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    //entitee liee est un string du type lie. ex: cadeau, utilisateur... etc
    //id entitee liee est le numero de l'entite
    #[Route('/{idEntiteeLiee}/{entiteeLiee}/new', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(int $idEntiteeLiee, string $entiteeLiee, Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $message = new Message();
        
        $form = $this->createForm(MessageType::class, $message,
            ['action'=>$this->generateUrl('app_message_new',[
                    'idEntiteeLiee'=>$idEntiteeLiee,
                    'entiteeLiee'=>$entiteeLiee
                ]),
                'method'=>'POST'
            ]);
                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setEntiteeLiee($entiteeLiee);
            $message->setDatePoste(new DateTime());
            $message->setIdEntiteeLiee($idEntiteeLiee);
            $message->setUtilisateurPoste($this->getUser());
            $entityManager->persist($message);
            $entityManager->flush();

            $route = $request->headers->get('referer');

            return $this->redirect($route);
            
        }

        return $this->render('message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $message->getUtilisateurPoste() == $this->getUser()) {
            $entityManager->flush();
            if($message->getEntiteeLiee() == "cadeau"){
                return $this->redirectToRoute('app_cadeau_show', ['id'=>$message->getIdEntiteeLiee()], Response::HTTP_SEE_OTHER);
            }else{
                return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        if (
            $this->isCsrfTokenValid('delete_message', $request->request->get('token'))
            && $message->getUtilisateurPoste() == $this->getUser()
        ) {
            $entityManager->remove($message);
            $entityManager->flush();
        }
        $route = $request->headers->get('referer');

        return $this->redirect($route);
    }

}
