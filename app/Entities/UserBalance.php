<?php

namespace App\Entities;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class UserBalance
{
    public int $id;
    public int $userId;
    public float $value;
    public float $balance;
    public Carbon $createdAt;

    public function __construct(array $data)
    {
        $this->id = (int) $data['id'];
        $this->userId = (int) $data['user_id'];
        $this->value = (float) $data['value'];
        $this->balance = (float) $data['balance'];
        $this->createdAt = new Carbon($data['created_at']);
    }

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public static function validate(array $data)
    {
        $validator = Validator::make($data, [
            'id' => 'required|integer',
            'user_id' => 'required|integer',
            'value' => 'required|numeric',
            'balance' => 'required|numeric',
            'created_at' => 'required|date',
        ]);

        return $validator;
    }
}
