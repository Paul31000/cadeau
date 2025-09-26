<?php

namespace App\Controller;

use DateTime;
use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
        
        $form = $this
                    ->createFormBuilder()
                    ->setAction($this->generateUrl('app_message_new',
                        ['idEntiteeLiee'=>$idEntiteeLiee,
                        'entiteeLiee'=>$entiteeLiee]
                    ))
                    ->add('text', TextType::class, [
                        'required'   => true,
                        'label' => 'message:*',
                        'sanitize_html' => true,
                        'constraints' => [new Length([
                            'max' => 5000,
                            "maxMessage"=>"Le texte ne peut pas excéder {{ limit }} caractères."
                        ])],
                    ])
                    ->getForm();
                

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setEntiteeLiee($entiteeLiee);
            $message->setDatePoste(new DateTime());
            $message->setIdEntiteeLiee($idEntiteeLiee);
            $message->setText($request->get('form')["text"]);
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

    /* #[Route('/{id}', name: 'app_message_show', methods: ['GET'])]
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    } */

    #[Route('/{id}/edit', name: 'app_message_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        $form = $form = $this
                            ->createFormBuilder($message)
                            ->setAction($this->generateUrl('app_message_edit',
                                ['id'=>$message->getId()]
                            ))
                            ->add('text', TextareaType::class, [
                                'row_attr' => ['class' => 'text-editor'],
                                'required'   => true,
                                'label' => 'message:*',
                                'sanitize_html' => true,
                                'constraints' => [new Length([
                                    'max' => 5000,
                                    "maxMessage"=>"Le texte ne peut pas excéder {{ limit }} caractères."
                                ])],
                            ])
                            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $message->getUtilisateurPoste() == $this->getUser()) {
            $entityManager->flush();

            return $this->redirectToRoute('accueil', [], Response::HTTP_SEE_OTHER);
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
