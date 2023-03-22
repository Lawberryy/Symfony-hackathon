<?php

namespace App\Entity;

use App\Repository\TrailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrailRepository::class)]
class Trail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $total_duration = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'trail_id', targetEntity: LinkTrail::class)]
    private Collection $linkTrails;

    public function __construct()
    {
        $this->linkTrails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalDuration(): ?\DateTimeInterface
    {
        return $this->total_duration;
    }

    public function setTotalDuration(\DateTimeInterface $total_duration): self
    {
        $this->total_duration = $total_duration;

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
            $linkTrail->setTrailId($this);
        }

        return $this;
    }

    public function removeLinkTrail(LinkTrail $linkTrail): self
    {
        if ($this->linkTrails->removeElement($linkTrail)) {
            // set the owning side to null (unless already changed)
            if ($linkTrail->getTrailId() === $this) {
                $linkTrail->setTrailId(null);
            }
        }

        return $this;
    }
}
