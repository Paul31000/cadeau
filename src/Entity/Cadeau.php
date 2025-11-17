<?php

namespace App\Entity;

use App\Repository\CadeauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CadeauRepository::class)]
class Cadeau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 600, nullable: true)]
    private ?string $urlRecomendee = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $taille = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $commentaires = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'estLeDestinataire')]
    private Collection $destinataireCadeau;

    #[ORM\ManyToOne(inversedBy: 'cadeaux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ListeCadeau $listeCadeau = null;

    /**
     * @var Collection<int, UtilisateurOffre>
     */
    #[ORM\OneToMany(targetEntity: UtilisateurOffre::class, mappedBy: 'cadeau' , cascade: ['persist'], orphanRemoval: true)]
    private Collection $utilisateurOffres;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(nullable: true)]
    private ?int $prix = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    

    public function __construct()
    {
        $this->destinataireCadeau = new ArrayCollection();
        $this->utilisateurOffres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlRecomendee(): ?string
    {
        return $this->urlRecomendee;
    }

    public function setUrlRecomendee(?string $urlRecomendee): static
    {
        $this->urlRecomendee = $urlRecomendee;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(?string $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): static
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getDestinataireCadeau(): Collection
    {
        return $this->destinataireCadeau;
    }

    public function addDestinataireCadeau(User $destinataireCadeau): static
    {
        if (!$this->destinataireCadeau->contains($destinataireCadeau)) {
            $this->destinataireCadeau->add($destinataireCadeau);
        }

        return $this;
    }

    public function removeDestinataireCadeau(User $destinataireCadeau): static
    {
        $this->destinataireCadeau->removeElement($destinataireCadeau);

        return $this;
    }

    public function getListeCadeau(): ?ListeCadeau
    {
        return $this->listeCadeau;
    }

    public function setListeCadeau(?ListeCadeau $listeCadeau): static
    {
        $this->listeCadeau = $listeCadeau;

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
            $utilisateurOffre->setCadeau($this);
        }

        return $this;
    }

    public function removeUtilisateurOffre(UtilisateurOffre $utilisateurOffre): static
    {
        if ($this->utilisateurOffres->removeElement($utilisateurOffre)) {
            // set the owning side to null (unless already changed)
            if ($utilisateurOffre->getCadeau() === $this) {
                $utilisateurOffre->setCadeau(null);
            }
        }

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(?int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }


    
}
