<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Repository\IndexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * IndexHistory controller class.
 */
final class IndexHistory extends AbstractController
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
     * @param IndexRepository $indexRepository
     * @return JsonResponse
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function __invoke(IndexRepository $indexRepository): JsonResponse
    {
        $iterable = $indexRepository->getHistory(7, true);

        $result = [];
        foreach ($iterable as $index) {
            if (!isset($result[ $index->getTag() ])) {
                $result[ $index->getTag() ] = [];
            }
            $result[ $index->getTag() ][] = [
                $index->getCreatedAt()->format('Y-m-d') => $index->getValue(),
            ];

            $this->entityManager->detach($index);
        }

        return $this->json($result);
    }
}
