<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_PSEUDO', fields: ['pseudo'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Cadeau>
     */
    #[ORM\ManyToMany(targetEntity: Cadeau::class, mappedBy: 'destinataireCadeau')]
    private Collection $estLeDestinataire;

    /**
     * @var Collection<int, GroupeCadeau>
     */
    #[ORM\ManyToMany(targetEntity: GroupeCadeau::class, mappedBy: 'UtilisateursConcernes')]
    private Collection $AppartientAuxGroupes;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'utilisateurPoste', orphanRemoval: true)]
    private Collection $messages;

    /**
     * @var Collection<int, ListeCadeau>
     */
    #[ORM\OneToMany(targetEntity: ListeCadeau::class, mappedBy: 'Utilisateur')]
    private Collection $ListeCadeaux;

    /**
     * @var Collection<int, Cadeau>
     */
    #[ORM\ManyToMany(targetEntity: Cadeau::class, mappedBy: 'UtilisateurOffre')]
    private Collection $offre;

    /**
     * @var Collection<int, UtilisateurOffre>
     */
    #[ORM\OneToMany(targetEntity: UtilisateurOffre::class, mappedBy: 'utilisateurConcerne')]
    private Collection $utilisateurOffres;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pseudo = null;

    /* A supprimer? */
    /**
     * @var Collection<int, ListeCadeau>
     */
    #[ORM\OneToMany(targetEntity: ListeCadeau::class, mappedBy: 'userA')]
    private Collection $listeCadeaus;

    public function __construct()
    {
        $this->estLeDestinataire = new ArrayCollection();
        $this->AppartientAuxGroupes = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->ListeCadeaux = new ArrayCollection();
        $this->offre = new ArrayCollection();
        $this->utilisateurOffres = new ArrayCollection();
        $this->listeCadeaus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @param list<string> $roles
     */
    public function addRole(String $role): static
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Cadeau>
     */
    public function getEstLeDestinataire(): Collection
    {
        return $this->estLeDestinataire;
    }

    public function addEstLeDestinataire(Cadeau $estLeDestinataire): static
    {
        if (!$this->estLeDestinataire->contains($estLeDestinataire)) {
            $this->estLeDestinataire->add($estLeDestinataire);
            $estLeDestinataire->addDestinataireCadeau($this);
        }

        return $this;
    }

    public function removeEstLeDestinataire(Cadeau $estLeDestinataire): static
    {
        if ($this->estLeDestinataire->removeElement($estLeDestinataire)) {
            $estLeDestinataire->removeDestinataireCadeau($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, GroupeCadeau>
     */
    public function getAppartientAuxGroupes(): Collection
    {
        return $this->AppartientAuxGroupes;
    }

    public function addAppartientAuxGroupe(GroupeCadeau $appartientAuxGroupe): static
    {
        if (!$this->AppartientAuxGroupes->contains($appartientAuxGroupe)) {
            $this->AppartientAuxGroupes->add($appartientAuxGroupe);
            $appartientAuxGroupe->addUtilisateursConcerne($this);
        }

        return $this;
    }

    public function removeAppartientAuxGroupe(GroupeCadeau $appartientAuxGroupe): static
    {
        if ($this->AppartientAuxGroupes->removeElement($appartientAuxGroupe)) {
            $appartientAuxGroupe->removeUtilisateursConcerne($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUtilisateurPoste($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUtilisateurPoste() === $this) {
                $message->setUtilisateurPoste(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ListeCadeau>
     */
    public function getListeCadeaux(): Collection
    {
        return $this->ListeCadeaux;
    }

    public function addListeCadeaux(ListeCadeau $listeCadeaux): static
    {
        if (!$this->ListeCadeaux->contains($listeCadeaux)) {
            $this->ListeCadeaux->add($listeCadeaux);
            $listeCadeaux->setUtilisateur($this);
        }

        return $this;
    }

    public function removeListeCadeaux(ListeCadeau $listeCadeaux): static
    {
        if ($this->ListeCadeaux->removeElement($listeCadeaux)) {
            // set the owning side to null (unless already changed)
            if ($listeCadeaux->getUtilisateur() === $this) {
                $listeCadeaux->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cadeau>
     */
    public function getOffre(): Collection
    {
        return $this->offre;
    }

    public function addOffre(Cadeau $offre): static
    {
        if (!$this->offre->contains($offre)) {
            $this->offre->add($offre);
            $offre->addUtilisateurOffre($this);
        }

        return $this;
    }

    public function removeOffre(Cadeau $offre): static
    {
        if ($this->offre->removeElement($offre)) {
            $offre->removeUtilisateurOffre($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, UtilisateurOffre>
     */
    public function getUtilisateurOffres(): Collection
    {
        return $this->utilisateurOffres;
    }

    public function addUtilisateurOffre(UtilisateurOffre $utilisateurOffre): static
    {
        if (!$this->utilisateurOffres->contains($utilisateurOffre)) {
            $this->utilisateurOffres->add($utilisateurOffre);
            $utilisateurOffre->setUtilisateurConcerne($this);
        }

        return $this;
    }

    public function removeUtilisateurOffre(UtilisateurOffre $utilisateurOffre): static
    {
        if ($this->utilisateurOffres->removeElement($utilisateurOffre)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurOffre->getUtilisateurConcerne() === $this) {
                $utilisateurOffre->setUtilisateurConcerne(null);
            }
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function __toString()
    {
        return $this->pseudo;
    }

    /**
     * @return Collection<int, ListeCadeau>
     */
    public function getListeCadeaus(): Collection
    {
        return $this->listeCadeaus;
    }

    public function addListeCadeau(ListeCadeau $listeCadeau): static
    {
        if (!$this->listeCadeaus->contains($listeCadeau)) {
            $this->listeCadeaus->add($listeCadeau);
            $listeCadeau->setUserA($this);
        }

        return $this;
    }

    public function removeListeCadeau(ListeCadeau $listeCadeau): static
    {
        if ($this->listeCadeaus->removeElement($listeCadeau)) {
            // set the owning side to null (unless already changed)
            if ($listeCadeau->getUserA() === $this) {
                $listeCadeau->setUserA(null);
            }
        }

        return $this;
    }
}
