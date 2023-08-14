<?php

namespace App\DataPersister;

use App\Entity\User;
use JetBrains\PhpStorm\NoReturn;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * UserDataPersister class.
 */
final class UserDataPersister implements DataPersisterInterface
{
    /**
     * @param ContextAwareDataPersisterInterface $decorated
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    /**
     * @param $data
     * @param array $context
     * @return bool
     */
    public function supports($data, array $context = []): bool
    {
        return $this->decorated->supports($data, $context);
    }

    /**
     * @param User $data
     * @return object|void
     */
    #[NoReturn] public function persist($data, array $context = [])
    {
        if ($data->getPlainPassword()) {
            $data->setPassword(
                $this->userPasswordHasher->hashPassword($data, $data->getPlainPassword())
            );
            $data->eraseCredentials();
        }

        $data->setUpdatedAt(new \DateTimeImmutable());

        return $this->decorated->persist($data, $context);
    }

    /**
     * @param $data
     * @param array $context
     * @return mixed
     */
    public function remove($data, array $context = []): mixed
    {
        return $this->decorated->remove($data, $context);
    }
}
