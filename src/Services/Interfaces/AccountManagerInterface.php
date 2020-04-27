<?php

namespace App\Services\Interfaces;

use App\Entity\Account;
use App\Repository\Interfaces\AccountRepositoryInterface;

interface AccountManagerInterface
{
    public function __construct(AccountRepositoryInterface $accountRepository);

    public function updateAccount($data, Account $account);
}