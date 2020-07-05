<?php

namespace App\Services\Interfaces;

use App\Entity\Notice;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

interface AdminServiceInterface
{
    public function __construct(EntityManagerInterface $entityManager);

    public function ban(User $user);

    public function unBan(User $user);

    public function activateNotice(Notice $notice);

    public function activateUser(User $user);
}