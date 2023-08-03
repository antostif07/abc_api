<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CourseRepository;
use App\Trait\CreatedAndUpdated;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ApiResource(
    normalizationContext: ['groups' => ['course.read']],
    denormalizationContext: ['groups' => ['course.write']],
)]
class Course
{
    use CreatedAndUpdated;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["course.read", "level.read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["course.read", "course.write", "level.read"])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["course.read", "course.write"])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["course.read", "course.write"])]
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[Groups(["course.read", "course.write"])]
    private ?Image $cover = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['course.read', 'course.write'])]
    private ?Center $center = null;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Level::class)]
    #[Groups(['course.read', 'course.write'])]
    private Collection $levels;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['course.read', 'course.write'])]
    private ?string $briefDescription = null;

    public function __construct()
    {
        $this->levels = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getCenter(): ?Center
    {
        return $this->center;
    }

    public function setCenter(?Center $center): self
    {
        $this->center = $center;

        return $this;
    }

    /**
     * @return Collection<int, Level>
     */
    public function getLevels(): Collection
    {
        return $this->levels;
    }

    public function addLevel(Level $level): self
    {
        if (!$this->levels->contains($level)) {
            $this->levels->add($level);
            $level->setCourse($this);
        }

        return $this;
    }

    public function removeLevel(Level $level): self
    {
        if ($this->levels->removeElement($level)) {
            // set the owning side to null (unless already changed)
            if ($level->getCourse() === $this) {
                $level->setCourse(null);
            }
        }

        return $this;
    }

    public function getBriefDescription(): ?string
    {
        return $this->briefDescription;
    }

    public function setBriefDescription(?string $briefDescription): self
    {
        $this->briefDescription = $briefDescription;

        return $this;
    }
}
