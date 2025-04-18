<?php

namespace App\Entity;

use App\Repository\UtilisateurOffreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurOffreRepository::class)]
class UtilisateurOffre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $montant = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurOffres')]
    private ?Cadeau $cadeau = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurOffres')]
    private ?User $utilisateurConcerne = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(?int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCadeau(): ?Cadeau
    {
        return $this->cadeau;
    }

    public function setCadeau(?Cadeau $cadeau): static
    {
        $this->cadeau = $cadeau;

        return $this;
    }

    public function getUtilisateurConcerne(): ?User
    {
        return $this->utilisateurConcerne;
    }

    public function setUtilisateurConcerne(?User $utilisateurConcerne): static
    {
        $this->utilisateurConcerne = $utilisateurConcerne;

        return $this;
    }
}
