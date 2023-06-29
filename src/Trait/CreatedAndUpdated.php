<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait CreatedAndUpdated
{
    #[ORM\Column]
    #[Groups(["course.read"])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["course.read"])]
    private ?\DateTimeImmutable $updated_at = null;

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    #[ORM\PreUpdate]
    public function updatedTime()
    {
        if($this->updated_at == null){
            $this->updated_at = new \DateTimeImmutable('now');
        }
    }
    
    #[ORM\PrePersist]
    public function updatedTimestamps()
    {
        if($this->created_at == null){
            $this->created_at = new \DateTimeImmutable('now');
        }
    }

}