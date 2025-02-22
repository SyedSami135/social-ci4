<?php

namespace App\Models;

use CodeIgniter\Model;

use function App\Helpers\hashPassword;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = ['username', 'email', 'password', 'created_at'];

    // Enable timestamps to automatically handle created_at, updated_at fields
    protected $useTimestamps = true;

    // Hash the password before inserting into the database
    protected $beforeInsert = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = hashPassword($data['data']['password']);
        }
        return $data;
    }
    public function emailExists($email)
    {
        return $this->where('email', $email)->first() !== null;
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
}
