<?php

namespace App\Service;

use App\Entity\Index;
use Doctrine\ORM\EntityManagerInterface;

class IndexManager
{
    /**
     * Constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param array $symbols
     * @return bool
     */
    public function bulkCreateFromSymbols(array $symbols): bool
    {
        if (empty($symbols)) {
            return false;
        }

        // Calc summary by tag.
        $summary = [];
        foreach ($symbols as $symbol) {
            foreach ($symbol->tags as $tag) {
                if (!isset($summary[ $tag ])) {
                    $summary[ $tag ]['sum'] = 0;
                    $summary[ $tag ]['count'] = 0;
                }
                $summary[ $tag ]['sum'] += $symbol->marketCap;
                $summary[ $tag ]['count']++;
            }
        }

        // Create indexes.
        foreach ($summary as $tag => $indexData) {
            $index = new Index();
            $index->setTag($tag);
            $index->setValue(number_format($indexData['sum'] / $indexData['count'], 1, '.', ''));
            $index->setCreatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($index);
        }

        $this->entityManager->flush();

        return true;
    }
}
