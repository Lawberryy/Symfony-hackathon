<?php

namespace App\Entity;

use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StationRepository::class)]
class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'stations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $icon_url = null;

    #[ORM\ManyToOne(inversedBy: 'stations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Domain $domain = null;

    #[ORM\OneToMany(mappedBy: 'station', targetEntity: Lift::class)]
    private Collection $lifts;

    #[ORM\OneToMany(mappedBy: 'station', targetEntity: Slope::class)]
    private Collection $slopes;

    #[ORM\OneToMany(mappedBy: 'station', targetEntity: Problem::class)]
    private Collection $problems;

    #[ORM\Column(nullable: true)]
    private ?int $notation = null;

    public function __construct()
    {
        $this->lifts = new ArrayCollection();
        $this->slopes = new ArrayCollection();
        $this->problems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIconUrl(): ?string
    {
        return $this->icon_url;
    }

    public function setIconUrl(?string $icon_url): self
    {
        $this->icon_url = $icon_url;

        return $this;
    }

    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    public function setDomain(?Domain $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return Collection<int, Lift>
     */
    public function getLifts(): Collection
    {
        return $this->lifts;
    }

    public function addLift(Lift $lift): self
    {
        if (!$this->lifts->contains($lift)) {
            $this->lifts->add($lift);
            $lift->setStation($this);
        }

        return $this;
    }

    public function removeLift(Lift $lift): self
    {
        if ($this->lifts->removeElement($lift)) {
            // set the owning side to null (unless already changed)
            if ($lift->getStation() === $this) {
                $lift->setStation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Slope>
     */
    public function getSlopes(): Collection
    {
        return $this->slopes;
    }

    public function addSlope(Slope $slope): self
    {
        if (!$this->slopes->contains($slope)) {
            $this->slopes->add($slope);
            $slope->setStation($this);
        }

        return $this;
    }

    public function removeSlope(Slope $slope): self
    {
        if ($this->slopes->removeElement($slope)) {
            // set the owning side to null (unless already changed)
            if ($slope->getStation() === $this) {
                $slope->setStation(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, Problem>
     */
    public function getProblems(): Collection
    {
        return $this->problems;
    }

    public function addProblem(Problem $problem): self
    {
        if (!$this->problems->contains($problem)) {
            $this->problems->add($problem);
            $problem->setStation($this);
        }

        return $this;
    }

    public function removeProblem(Problem $problem): self
    {
        if ($this->problems->removeElement($problem)) {
            // set the owning side to null (unless already changed)
            if ($problem->getStation() === $this) {
                $problem->setStation(null);
            }
        }

    public function getNotation(): ?int
    {
        return $this->notation;
    }

    public function setNotation(?int $notation): self
    {
        $this->notation = $notation;


        return $this;
    }
}
