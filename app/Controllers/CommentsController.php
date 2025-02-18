<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CommentModel;
use CodeIgniter\HTTP\ResponseInterface;

class CommentsController extends BaseController
{
    public function index($id)
    {
        $commentModel = new CommentModel();
        $comments = $commentModel->findByPostId($id);
        return $this->response->setJSON($comments);
    }

    public function create($id)
    {
        $commentModel = new CommentModel();
        $data = [
            'post_id' => $id,
            'user_id' => 1,
            'comment' => $this->request->getPost('comment'),
        ];
        $commentModel->insert($data);
        return $this->response->setStatusCode(ResponseInterface::HTTP_CREATED)->setJSON([
            "message" => "Comment created successfully.",
            'data' => [
                ...$data,
                'comment_id' => $commentModel->insertID(),  // Returning the ID of the newly created post

            ]
        ]);
    }

    public function update($id)
    {
        $commentModel = new CommentModel();
       

        $comment = $commentModel->find($id);
        if (!$comment) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON([
                "message" => "Comment not found."
            ]);
        }

        $newComment = $this->request->getPost('comment');
        if (empty($newComment)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)->setJSON([
                "message" => "Comment cannot be empty."
            ]);
        }

        $data = [
            'comment' => $newComment,
        ];

        try {
            $commentModel->update($id, $data);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON([
                "message" => $e->getMessage()
            ]);
        }

        return $this->response->setJSON([
            "message" => "Comment updated successfully.",
            "data" => $data
        ]);
    }


    public function delete($id)
    {
        $commentModel = new CommentModel();
        $comment = $commentModel->find($id);
        if (!$comment) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON([
                "message" => "Comment not found."
            ]);
        }

        $commentModel->delete($id);

        return $this->response->setJSON([
            "message" => "Comment deleted successfully."
        ]);
    }
}
