<?php

namespace App\Entity;

use App\Repository\LiftRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LiftRepository::class)]
class Lift
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
	
	#[ORM\Column(type: Types::TIME_MUTABLE)]
         	private ?\DateTimeInterface $first_hour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $last_hour = null;

    #[ORM\Column(nullable: true)]
    private ?bool $exception = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $exception_message = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $Peak_Hour = null;

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

    public function getPeakHour(): ?\DateTimeInterface
    {
        return $this->Peak_Hour;
    }

    public function setPeakHour(?\DateTimeInterface $Peak_Hour): self
    {
        $this->Peak_Hour = $Peak_Hour;

        return $this;
    }
}
