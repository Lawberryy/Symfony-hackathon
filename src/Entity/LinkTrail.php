<?php

namespace App\Entity;

use App\Repository\LinkTrailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LinkTrailRepository::class)]
class LinkTrail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'linkTrails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trail $trail_id = null;

    #[ORM\ManyToOne(inversedBy: 'linkTrails')]
    private ?Lift $lift_id = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\ManyToOne(inversedBy: 'linkTrails')]
    private ?Slope $slope_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrailId(): ?Trail
    {
        return $this->trail_id;
    }

    public function setTrailId(?Trail $trail_id): self
    {
        $this->trail_id = $trail_id;

        return $this;
    }

    public function getLiftId(): ?Lift
    {
        return $this->lift_id;
    }

    public function setLiftId(?Lift $lift_id): self
    {
        $this->lift_id = $lift_id;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSlopeId(): ?Slope
    {
        return $this->slope_id;
    }

    public function setSlopeId(?Slope $slope_id): self
    {
        $this->slope_id = $slope_id;

        return $this;
    }
}
