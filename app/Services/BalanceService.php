<?php

namespace App\Services;

use App\Entities\UserBalance;
use Illuminate\Support\Facades\Log;

class BalanceService
{
    private $client = null;

    public function __construct(\Tochka\JsonRpcClient\Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get users balance
     * @param int $userId
     * @return null|UserBalance
     * @throws \Exception
     */
    public function getUsersBalance(int $userId): ?UserBalance
    {
        $result = null;

        $data = (array) $this->client->call('balance_userBalance', [
            $userId,
        ]);

        if (!empty($data)) {
            $validator = UserBalance::validate($data);
            if ($validator->fails()) {
                Log::error($validator->errors());
                throw new \Exception('Wrong response');
            }

            $result = new UserBalance($data);
        }

        return $result;
    }

    /**
     * Get users payment history
     * @param int $limit
     * @param int $page
     * @param int $userId
     * @return null|array
     *         - data  UserBalance[] Paginated history
     *         - total int           Total records
     * @throws \Exception
     */
    public function getBalanceHistory(int $limit, int $page, int $userId): ?array
    {
        $result = [];

        $data = (array) $this->client->call('balance_history', [
            $limit,
            $page,
            $userId,
        ]);

        if (!empty($data) && array_key_exists('data', $data)) {
            foreach ($data['data'] as $item) {
                $item = (array) $item;
                $validator = UserBalance::validate($item);
                if ($validator->fails()) {
                    Log::error($validator->errors());
                    throw new \Exception('Wrong response');
                }

                $result['data'][] = new UserBalance($item);
            }

            $result['total'] = $data['total'];
        }

        return $result ?: null;
    }
}
