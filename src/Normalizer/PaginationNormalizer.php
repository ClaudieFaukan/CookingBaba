<?php

namespace App\Normalizer;


use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormalizer implements NormalizerInterface
{

    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer
        )
    {
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {

        if (!$object instanceof PaginationInterface) {
             throw new \InvalidArgumentException('The object must implement PaginationInterface.');
             return null;
        }

        return [
            'current_page' => $object->getCurrentPageNumber(),
            'total_items' => $object->getTotalItemCount(),
            'items_per_page' => $object->getItemNumberPerPage(),
            'total_pages' => ceil($object->getTotalItemCount() / $object->getItemNumberPerPage()),
            'items' => array_map(fn($item) => $this->normalizer->normalize($item, $format, $context), iterator_to_array($object->getItems())),

        ];
    }

   
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool{
        return $data instanceof PaginationInterface;
    }

  
    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginationInterface::class => true,
        ];
    }
}