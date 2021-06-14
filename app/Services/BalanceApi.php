<?php

namespace app\Services;

/**
 * Class Client
 *
 * @method mixed balance_userBalance(int $userId)
 * @method mixed balance_history(int $limit, int $page, int $userId)
 */
class BalanceApi extends \Tochka\JsonRpcClient\Client
{
    /**
     * @param int $userId
     * @return void
     */
    public function balance_userBalance($userId)
    {
    }

    /**
     * @param int $limit
     * @param int $page
     * @param int $userId
     * @return void
     */
    public function balance_history($limit, $page, $userId)
    {
    }
}
