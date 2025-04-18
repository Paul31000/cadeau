<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePoste = null;

    #[ORM\Column(length: 5000, nullable: true)]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $utilisateurPoste = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $entiteeLiee = null;

    #[ORM\Column(nullable: true)]
    private ?int $idEntiteeLiee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePoste(): ?\DateTimeInterface
    {
        return $this->datePoste;
    }

    public function setDatePoste(?\DateTimeInterface $datePoste): static
    {
        $this->datePoste = $datePoste;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getUtilisateurPoste(): ?User
    {
        return $this->utilisateurPoste;
    }

    public function setUtilisateurPoste(?User $utilisateurPoste): static
    {
        $this->utilisateurPoste = $utilisateurPoste;

        return $this;
    }

    public function getEntiteeLiee(): ?string
    {
        return $this->entiteeLiee;
    }

    public function setEntiteeLiee(string $entiteeLiee): static
    {
        $this->entiteeLiee = $entiteeLiee;

        return $this;
    }

    public function getIdEntiteeLiee(): ?int
    {
        return $this->idEntiteeLiee;
    }

    public function setIdEntiteeLiee(?int $idEntiteeLiee): static
    {
        $this->idEntiteeLiee = $idEntiteeLiee;

        return $this;
    }
}
