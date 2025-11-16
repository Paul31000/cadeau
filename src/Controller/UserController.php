<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mime\Address;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTHENTIFIE');
        
        $demandes=$this->getUser()->getAmiDemande();
        
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'demandes' => $demandes,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dump($request->get('user')["password"]);

            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $request->get('user')["password"]['first']
            );
            $user->setPassword($hashedPassword);
            $user->addRole('ROLE_AUTHENTIFIE');
            $user->setDateCreation(new DateTime());

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTHENTIFIE');
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTHENTIFIE');
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /* #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    } */

    #[Route('/requeteLutin/{id}', name: 'app_requete_ami', methods: ['POST'])]
    public function addLutin(Request $request, User $user, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTHENTIFIE');
        if ($this->isCsrfTokenValid('requete_ami'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $user->addAmiDemande($this->getUser());
            $entityManager->flush();
            
            $email = (new TemplatedEmail())
                ->from(new Address('noreply@paul-s.fr', 'Votre atelier noel'))
                ->to((string) $user->getEmail())
                ->subject('Une personne du site vous a ajouté en ami.')
                ->htmlTemplate('email/emailAddAmi.html.twig')
                ->context([
                    'demandeur' => $this->getUser(),
                ])
            ;

            $mailer->send($email);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/confirmerLutin/{id}', name: 'app_user_confirm_ami', methods: ['POST'])]
    public function confirmerLutin(Request $request, User $user, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTHENTIFIE');
        if ($this->isCsrfTokenValid('confirmer_ami'.$user->getId(), $request->getPayload()->getString('_token'))) {
            foreach($this->getUser()->getAmiDemande() as $ami){
                if($ami->getId()==$user->getId()){
                    $this->getUser()->addAmi($user);
                    $user->addAmi($this->getUser());
                    $this->getUser()->removeAmiDemande($user);
                    $entityManager->flush();
                }  
            }

            $pseudoReponse=$this->getUser()->getPseudo();
            $email = (new TemplatedEmail())
                ->from(new Address('noreply@paul-s.fr', 'Votre atelier noel'))
                ->to((string) $user->getEmail())
                ->subject("$pseudoReponse du site Paul-s a répondu oui à votre requête d\'ami.")
                ->htmlTemplate('email/emailReponseOkAmi.html.twig')
                ->context([
                    'demandeur' => $this->getUser(),
                ])
            ;

            $mailer->send($email);
            
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('supprimerLutin/{id}', name: 'app_user_delete_ami', methods: ['POST'])]
    public function supprimerLutin(Request $request, User $user, MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTHENTIFIE');
        if ($this->isCsrfTokenValid('refuser_ami'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $user->removeAmiDemande($this->getUser());
            $entityManager->flush();

            $pseudoReponse=$this->getUser()->getPseudo();
            $email = (new TemplatedEmail())
                ->from(new Address('noreply@paul-s.fr', 'Votre atelier noel'))
                ->to((string) $user->getEmail())
                ->subject("$pseudoReponse du site Paul-s a répondu non à votre requête d\'ami.")
                ->htmlTemplate('email/emailReponseNonAmi.html.twig')
                ->context([
                    'demandeur' => $this->getUser(),
                ])
            ;

            $mailer->send($email);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    } 

}
