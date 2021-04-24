<?php

namespace App\Services;

use App\Models\User as UserModel;
use Illuminate\Support\Facades\App;

class User
{

    /**
     * Sync User with User ID
     * @param int $user_id
     * @return UserModel
     * @throws \Throwable
     */
    public static function syncWithId(int $user_id): UserModel
    {

        $user = UserModel::find($user_id);

        throw_if(!$user,
            new \RuntimeException('Invalid user id')
        );

        return $user;

    }

    /**
     * Get User Wallet Balance
     * @param UserModel $user
     * @return array
     */
    public static function getMyWallet(UserModel $user): array
    {

        try {

            $ballance = $user->getBalance();

            return ['code' => 0, 'message' => ['ballance' => $ballance]];

        } catch (Throwable $e) {
            return ['code' => 100, 'message' => $e->getMessage()];
        }

    }

}
