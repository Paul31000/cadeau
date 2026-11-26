<?php

namespace App\Entity;

use App\Repository\PhantomUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PhantomUserRepository::class)]
#[UniqueEntity(
    fields: ['Mail'],
    message: 'Le mail existe déjà.',
    errorPath: 'mail'
)]
class PhantomUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    /**
     * @var Collection<int, GroupeCadeau>
     */
    #[ORM\ManyToMany(targetEntity: GroupeCadeau::class, inversedBy: 'phantomUsers')]
    private Collection $groupeCadeau;

    public function __construct()
    {
        $this->groupeCadeau = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return Collection<int, GroupeCadeau>
     */
    public function getGroupeCadeau(): Collection
    {
        return $this->groupeCadeau;
    }

    public function addGroupeCadeau(GroupeCadeau $groupeCadeau): static
    {
        if (!$this->groupeCadeau->contains($groupeCadeau)) {
            $this->groupeCadeau->add($groupeCadeau);
        }

        return $this;
    }

    public function removeGroupeCadeau(GroupeCadeau $groupeCadeau): static
    {
        $this->groupeCadeau->removeElement($groupeCadeau);

        return $this;
    }
}
