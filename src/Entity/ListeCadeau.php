<?php

namespace App\Entity;

use App\Repository\ListeCadeauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListeCadeauRepository::class)]
class ListeCadeau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Cadeau>
     */
    #[ORM\OneToMany(targetEntity: Cadeau::class, mappedBy: 'listeCadeau', orphanRemoval: true)]
    private Collection $cadeaux;

    //le proprietaire de la liste de cadeau
    #[ORM\ManyToOne(inversedBy: 'ListeCadeaux')]
    private ?User $utilisateur = null;

    //a supprimer?
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titre = null;

    //a supprimer?
    #[ORM\ManyToOne(inversedBy: 'listeCadeaus')]
    private ?User $userA = null;

    #[ORM\ManyToOne(inversedBy: 'listeCadeaux')]
    private ?GroupeCadeau $groupeCadeau = null;

    public function __construct()
    {
        $this->cadeaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Cadeau>
     */
    public function getCadeaux(): Collection
    {
        return $this->cadeaux;
    }

    public function addCadeaux(Cadeau $cadeaux): static
    {
        if (!$this->cadeaux->contains($cadeaux)) {
            $this->cadeaux->add($cadeaux);
            $cadeaux->setListeCadeau($this);
        }

        return $this;
    }

    public function removeCadeaux(Cadeau $cadeaux): static
    {
        if ($this->cadeaux->removeElement($cadeaux)) {
            // set the owning side to null (unless already changed)
            if ($cadeaux->getListeCadeau() === $this) {
                $cadeaux->setListeCadeau(null);
            }
        }

        return $this;
    }

    public function getUtilisateur(): ?User
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?User $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getUserA(): ?User
    {
        return $this->userA;
    }

    public function setUserA(?User $userA): static
    {
        $this->userA = $userA;

        return $this;
    }

    public function getGroupeCadeau(): ?GroupeCadeau
    {
        return $this->groupeCadeau;
    }

    public function setGroupeCadeau(?GroupeCadeau $groupeCadeau): static
    {
        $this->groupeCadeau = $groupeCadeau;

        return $this;
    }
}
