<?php

namespace App\Entity;

use App\Repository\SlopeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SlopeRepository::class)]
class Slope
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'lifts')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Station $station = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $difficulty = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $first_hour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $last_hour = null;

    #[ORM\Column(nullable: true)]
    private ?bool $exception = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $exception_message = null;


    #[ORM\OneToMany(mappedBy: 'slope_id', targetEntity: LinkTrail::class)]
    private Collection $linkTrails;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $duration = null;

    public function __construct()
    {
        $this->linkTrails = new ArrayCollection();
    }

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Peak_Hour = null;

    #[ORM\Column(nullable: true)]
    private ?int $snow_quality = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): self
    {
        $this->station = $station;

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

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getFirstHour(): ?\DateTimeInterface
    {
        return $this->first_hour;
    }

    public function setFirstHour(\DateTimeInterface $first_hour): self
    {
        $this->first_hour = $first_hour;

        return $this;
    }

    public function getLastHour(): ?\DateTimeInterface
    {
        return $this->last_hour;
    }

    public function setLastHour(\DateTimeInterface $last_hour): self
    {
        $this->last_hour = $last_hour;

        return $this;
    }

    public function isException(): ?bool
    {
        return $this->exception;
    }

    public function setException(?bool $exception): self
    {
        $this->exception = $exception;

        return $this;
    }

    public function getExceptionMessage(): ?string
    {
        return $this->exception_message;
    }

    public function setExceptionMessage(?string $exception_message): self
    {
        $this->exception_message = $exception_message;

        return $this;
    }


    /**
     * @return Collection<int, LinkTrail>
     */
    public function getLinkTrails(): Collection
    {
        return $this->linkTrails;
    }

    public function addLinkTrail(LinkTrail $linkTrail): self
    {
        if (!$this->linkTrails->contains($linkTrail)) {
            $this->linkTrails->add($linkTrail);
            $linkTrail->setSlopeId($this);
        }
    }

    public function getPeakHour(): ?\DateTimeInterface
    {
        return $this->Peak_Hour;
    }

    public function setPeakHour(?\DateTimeInterface $Peak_Hour): self
    {
        $this->Peak_Hour = $Peak_Hour;

        return $this;
    }


    public function removeLinkTrail(LinkTrail $linkTrail): self
    {
        if ($this->linkTrails->removeElement($linkTrail)) {
            // set the owning side to null (unless already changed)
            if ($linkTrail->getSlopeId() === $this) {
                $linkTrail->setSlopeId(null);
            }
        }

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
    public function getSnowQuality(): ?int
    {
        return $this->snow_quality;
    }

    public function setSnowQuality(?int $snow_quality): self
    {
        $this->snow_quality = $snow_quality;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
