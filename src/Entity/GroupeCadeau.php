<?php

namespace App\Entity;

use App\Repository\GroupeCadeauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupeCadeauRepository::class)]
class GroupeCadeau
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'AppartientAuxGroupes')]
    private Collection $utilisateurConcernes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeGroupe = null;

    #[ORM\Column(length: 255)]
    private ?string $nomGroupe = null;

    /**
     * @var Collection<int, ListeCadeau>
     */
    #[ORM\OneToMany(targetEntity: ListeCadeau::class, mappedBy: 'groupeCadeau')]
    private Collection $listeCadeaux;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;

    public function __construct()
    {
        $this->utilisateurConcernes = new ArrayCollection();
        $this->listeCadeaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getutilisateurConcernes(): Collection
    {
        return $this->utilisateurConcernes;
    }

    public function addUtilisateursConcerne(User $utilisateursConcerne): static
    {
        if (!$this->utilisateurConcernes->contains($utilisateursConcerne)) {
            $this->utilisateurConcernes->add($utilisateursConcerne);
        }

        return $this;
    }

    public function removeUtilisateursConcerne(User $utilisateursConcerne): static
    {
        $this->utilisateurConcernes->removeElement($utilisateursConcerne);

        return $this;
    }

    public function getTypeGroupe(): ?string
    {
        return $this->typeGroupe;
    }

    public function setTypeGroupe(?string $typeGroupe): static
    {
        $this->typeGroupe = $typeGroupe;

        return $this;
    }

    public function getNomGroupe(): ?string
    {
        return $this->nomGroupe;
    }

    public function setNomGroupe(string $nomGroupe): static
    {
        $this->nomGroupe = $nomGroupe;

        return $this;
    }

    /**
     * @return Collection<int, ListeCadeau>
     */
    public function getListeCadeaux(): Collection
    {
        return $this->listeCadeaux;
    }

    public function addListeCadeaux(ListeCadeau $listeCadeaux): static
    {
        if (!$this->listeCadeaux->contains($listeCadeaux)) {
            $this->listeCadeaux->add($listeCadeaux);
            $listeCadeaux->setGroupeCadeau($this);
        }

        return $this;
    }

    public function removeListeCadeaux(ListeCadeau $listeCadeaux): static
    {
        if ($this->listeCadeaux->removeElement($listeCadeaux)) {
            // set the owning side to null (unless already changed)
            if ($listeCadeaux->getGroupeCadeau() === $this) {
                $listeCadeaux->setGroupeCadeau(null);
            }
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}
