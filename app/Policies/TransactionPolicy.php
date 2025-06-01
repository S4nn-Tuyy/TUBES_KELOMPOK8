<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function view(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->buyer_id || $user->id === $transaction->seller_id;
    }

    public function update(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->seller_id;
    }

    public function confirm(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->buyer_id;
    }

    public function cancel(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->buyer_id || $user->id === $transaction->seller_id;
    }
} 