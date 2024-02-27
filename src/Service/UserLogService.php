<?php

namespace App\Service;

use App\Entity\User\User;
use App\Entity\User\UserLog;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class UserLogService
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function addToLog(
        User   $user,
        string $message,
        string $type
    ): void
    {
        $userLog = new UserLog();

        $userLog
            ->setCreatedAt(new DateTime('now'))
            ->setUser($user)
            ->setMessage($message)
            ->setType($type);

        $this->entityManager->persist($userLog);
        $this->entityManager->flush();
    }
}
