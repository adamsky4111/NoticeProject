<?php

namespace App\Services;

use App\Entity\Account;
use App\Entity\Notice;
use App\Entity\User;
use App\Services\Interfaces\AdminServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class AdminService implements AdminServiceInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function ban(User $user)
    {
        $user->setIsBanned(true);

        $this->save($user);
    }

    public function unBan(User $user)
    {
        $user->setIsBanned(false);

        $this->save($user);
    }

    public function activateNotice(Notice $notice)
    {
        $notice->setIsActive(true);

        $this->save($notice);
    }

    public function activateUser(User $user)
    {
        $user->setIsActive(true);
        if ($user->getAccount() !== null) {
            $user->setAccount(new Account());
        }

        $this->save($user);
    }

    private function save(object $object)
    {
        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}