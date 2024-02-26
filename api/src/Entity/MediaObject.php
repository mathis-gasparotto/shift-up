<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\CreateMediaObjectAction;
use App\Helper\GlobalHelper;
use App\Model\TracingAwareInterface;
use App\Model\Traits\TracingAwareTrait;
use App\Repository\MediaObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: MediaObjectRepository::class)]
#[ApiResource(
    types: [
        'https://schema.org/MediaObject'
    ],
    operations: [
        new Post(
            uriTemplate: '/media_objects',
            controller: CreateMediaObjectAction::class,
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            normalizationContext: [
                'openapi_definition_name' => 'PostCollection'
            ],
            denormalizationContext: [
                'groups' => [
                    'media_object:write'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")',
            validationContext: ['Default', 'media_object_create'],
            deserialize: false
        ),
        new GetCollection(
            uriTemplate: '/media_objects',
            normalizationContext: [
                'openapi_definition_name' => 'GetCollection',
                'groups' => [
                    'media_object:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")'
        ),
        new Get(
            uriTemplate: '/media_objects/{id}',
            requirements: [
                'id' => '^[a-z0-9]+(?:-[a-z0-9]+)*$'
            ],
            normalizationContext: [
                'openapi_definition_name' => 'GetItem',
                'groups' => [
                    'media_object:read',
                    'media_object:item:read'
                ]
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_USER . '")'
        ),
        new Delete(
            normalizationContext: [
                'openapi_definition_name' => 'DeleteItem'
            ],
            security: 'is_granted("' . GlobalHelper::ROLE_ADMIN . '")'
        )
    ],
    order: [
        'createdAt' => 'DESC'
    ]
)]
#[Vich\Uploadable]
class MediaObject implements TracingAwareInterface
{
    use TracingAwareTrait;

    /**
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator('doctrine.uuid_generator')]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[Groups(['media_object:read'])]
    private ?Uuid $id = null;

    /**
     * @var string|null
     *
     */
    #[
        ApiProperty(types: ["http://schema.org/contentUrl"]),
        Groups(['media_object:read'])
    ]
    private ?string $contentUrl;

    /**
     * @var File|null
     *
     */
    #[Vich\UploadableField(mapping: 'file', fileNameProperty: 'filePath')]
    #[
        Assert\NotNull(groups: ["media_object:create"]),
        Assert\Valid(),
        Assert\File(
            maxSize: "2M",
            mimeTypes: ["application/pdf", "image/jpeg", "image/jpg", "image/png"],
            maxSizeMessage: "Your file size is {{ size }}, but the limit is {{ limit }}",
            mimeTypesMessage: "Please upload a PDF, JPEG or PNG file"
        )
    ]
    public ?File $file = null;

    /**
     * @var
     */
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private $filePath;

    /**
     * @var array
     */
    #[Groups(['media_object:read'])]
    public array $pictures;


    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getContentUrl(): ?string
    {
        return $this->contentUrl;
    }

    /**
     * @param string $contentUrl
     * @return $this
     */
    public function setContentUrl(string $contentUrl): self
    {
        $this->contentUrl = $contentUrl;

        return $this;
    }

    /**
     * @param File|null $file
     * @return MediaObject
     */
    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * @param string|null $filePath
     * @return $this
     */
    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     * @return MediaObject
     */
    public function setFilename($filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return MediaObject
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return array
     */
    public function getPictures(): array
    {
        return $this->pictures;
    }

    /**
     * @param array $pictures
     * @return MediaObject
     */
    public function setPictures(array $pictures): self
    {
        $this->pictures = $pictures;

        return $this;
    }
}
