<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 *
 */
class CustomDenormalizer extends ObjectNormalizer
{
    /**
     * @param $data
     * @param string $type
     * @param string|null $format
     * @param array $context
     * @return mixed|object|null
     * @throws \ReflectionException
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $reflectionClass = new \ReflectionClass($type);
        foreach ($reflectionClass->getProperties() as $property) {
            $serializedName = $property->getAttributes(SerializedName::class)[0] ?? null;
            $serializedNameValue = $serializedName?->getArguments()[0] ?? null;
            if ($serializedNameValue && isset($data[$serializedNameValue])) {
                $data[$property->getName()] = $data[$serializedNameValue];
                unset($data[$serializedNameValue]);
            }
        }

        return parent::denormalize($data, $type, $format, $context);
    }
}