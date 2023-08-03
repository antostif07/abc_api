<?php
// api/src/Entity/Image.php
namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Controller\CreateImageController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity]
#[ApiResource(
    normalizationContext: ['groups' => ['image:read']], 
    types: ['https://schema.org/Image'],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            controller: CreateImageController::class, 
            deserialize: false, 
            validationContext: ['groups' => ['Default', 'image_create']], 
            openapi: new Model\Operation(
                requestBody: new Model\RequestBody(
                    content: new \ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object', 
                                'properties' => [
                                    'file' => [
                                        'type' => 'string', 
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ])
                )
            )
        )
    ]
)]
class Image
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    #[Groups(['image:read', "course.read", 'level.read'])]
    private ?int $id = null;

    #[ApiProperty(types: ['https://schema.org/contentUrl'])]
    #[Groups(['image:read', "course.read", 'level.read'])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "images", fileNameProperty: "filePath")]
    #[Assert\NotNull(groups: ['image_create'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)] 
    public ?string $filePath = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}