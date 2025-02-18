<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'title', 'content'];

    public function findById(int $id): ?array
    {
        return $this->where('id', $id)->first();
    }

    public function findByUserId(int $userId): array
    {
        return $this->where('user_id', $userId)->findAll();
    }
}
