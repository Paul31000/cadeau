<?php

namespace App\Controller;

use App\Entity\GroupeCadeau;
use App\Entity\PhantomUser;
use App\Form\GroupeCadeauType;
use App\Repository\PhantomUserRepository;
use App\Repository\GroupeCadeauRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

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
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, PhantomUserRepository $phantomUserRepository): Response
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

            $mails = $this->splitMails((string) $form->get('mails')->getData());
            
            $this->createPhantomUsers($mails, $groupeCadeau, $entityManager, $phantomUserRepository);
            $this->sendMailToPhantomUsers($mails,$mailer,$this->getUser(),$groupeCadeau);
            
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
    public function edit(Request $request, GroupeCadeau $groupeCadeau, EntityManagerInterface $entityManager, MailerInterface $mailer, PhantomUserRepository $phantomUserRepository): Response
    {
        //dump($groupeCadeau->getutilisateurConcernes());
        $form = $this->createForm(GroupeCadeauType::class, $groupeCadeau,['users'=>$this->getUser()->getAmi()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $mails = $this->splitMails((string) $form->get('mails')->getData());
            if(count($mails)>0){
                $this->createPhantomUsers($mails, $groupeCadeau, $entityManager, $phantomUserRepository);
                $this->sendMailToPhantomUsers($mails,$mailer,$this->getUser(),$groupeCadeau);
            }

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

    private function createPhantomUsers(array $mails, GroupeCadeau $groupeCadeau, EntityManagerInterface $entityManager, PhantomUserRepository $phantomUserRepository): void
    {
        foreach ($mails as $mail) {
            $phantomUser = $phantomUserRepository->findPhantomUserMatch($mail);

            if (null === $phantomUser) {
                $phantomUser = new PhantomUser();
                $phantomUser->setMail($mail);
                $entityManager->persist($phantomUser);
            }

            $groupeCadeau->addPhantomUser($phantomUser);
        }

        $entityManager->flush();
    }

    function splitMails(string $mails): array
    {
        $addresses = preg_split('/[,;\s]+/', trim($mails), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return array_values(array_unique($addresses));
    }
    
    function sendMailToPhantomUsers($mails,$mailer,$user,$groupeCadeau){
        foreach($mails as $mail){
            $email = (new TemplatedEmail())
                ->from(new Address('noreply@paul-s.fr', 'Votre atelier noel'))
                ->to((string) $mail)
                ->subject('Une personne du site preeesents.fr vous a ajouté à son évenement')
                ->htmlTemplate('email/emailPreeesents.html.twig')
                ->context([
                    'user'=>$user,
                    'groupeCadeau'=>$groupeCadeau,
                    'url' => $this->generateUrl('app_user_new'),
                ])
            ;
            $mailer->send($email);
        }
        
    }
    
}
