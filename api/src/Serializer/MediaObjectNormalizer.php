<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\MediaObject;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 *
 */
class MediaObjectNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     *
     */
    private const ALREADY_CALLED = 'MEDIA_OBJECT_NORMALIZER_ALREADY_CALLED';

    /**
     * MediaNormalizer constructor.
     * @param StorageInterface $storage
     * @param string $prefixUrl
     */
    public function __construct(private readonly StorageInterface $storage, private readonly string $prefixUrl) {}

    /**
     * @param $object
     * @param string|null $format
     * @param array $context
     * @return array
     * @throws ExceptionInterface
     */
    public function normalize($object, ?string $format = null, array $context = []): array
    {
        $context[self::ALREADY_CALLED] = true;

        $path = $this->storage->resolvePath($object, 'file');

        if (
            $context['root_operation_name'] === 'get_project_media_objects' ||
            in_array('project:read', $context['groups']) ||
            in_array('project:item:read', $context['groups'])
        ) {
            $object->setContentUrl($object->getFilePath());
        } else if ($path) {
            $object->setContentUrl($this->prefixUrl . $path);
            $object->pictures = [
                'thumb' => $this->prefixUrl . '/cache/picture_thumb/' . $path,
                'medium' => $this->prefixUrl . '/cache/picture_small/' . $path,
                'large' => $this->prefixUrl . '/cache/picture_large/' . $path
            ];
        }

        return $this->normalizer->normalize($object, $format, $context);
    }

    /**
     * @param $data
     * @param string|null $format
     * @param array $context
     * @return bool
     */
    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }


        return $data instanceof MediaObject;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            MediaObject::class => true, // true = supports normalization & denormalization
        ];
    }
}
