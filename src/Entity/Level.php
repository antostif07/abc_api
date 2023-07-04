<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LevelRepository;
use App\Trait\CreatedAndUpdated;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LevelRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["level.read"]],
    denormalizationContext: ["groups" => ["level.write"]]
)]
#[UniqueEntity(
    fields: ["course", "degree"],
    errorPath: 'degree',
    message: 'This level exist for the current course.'
    )]
class Level
{
    use CreatedAndUpdated;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['level.read', 'course.read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['level.read', 'level.write', 'course.read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['level.read', 'level.write',])]
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[Groups(['level.read', 'level.write',])]
    private ?Image $cover = null;

    #[ORM\OneToMany(mappedBy: 'level', targetEntity: Chapter::class, orphanRemoval: true)]
    #[Groups(['level.read'])]
    private Collection $chapters;

    #[ORM\ManyToOne(inversedBy: 'levels')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['level.read', 'level.write',])]
    private ?Course $course = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['level.read', 'level.write',])]
    private ?int $degree = null;

    public function __construct()
    {
        $this->chapters = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable('now');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCover(): ?Image
    {
        return $this->cover;
    }

    public function setCover(?Image $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return Collection<int, Chapter>
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
            $chapter->setLevel($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getLevel() === $this) {
                $chapter->setLevel(null);
            }
        }

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getDegree(): ?int
    {
        return $this->degree;
    }

    public function setDegree(int $degree): self
    {
        $this->degree = $degree;

        return $this;
    }

    #[ORM\PrePersist]
    public function updatedTimestamps()
    {
        if($this->created_at == null){
            $this->created_at = new \DateTimeImmutable('now');
        }
    }
}
