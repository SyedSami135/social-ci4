<?php

namespace App\Models;

use CodeIgniter\Model;

class CommentModel extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['post_id', 'user_id', 'comment'];

    protected $useTimestamps = true;

    public function findByPostId(int $postId): array
    {
        return $this->where('post_id', $postId)->findAll();
    }

    
}
