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
        $session = session(); // No need to use Services::session() within a controller
        $userId = $session->get('userId');

        $commentModel = new CommentModel();
        $data = [
            'post_id' => $id,
            'user_id' => $userId,
            'comment' => $this->request->getPost('comment'),
        ];
        $commentModel->insert($data);
        return sendResponse([...$data, 'comment_id' => $commentModel->insertID()], "Comment created successfully.");
    }

    public function update($id)
    {
        $commentModel = new CommentModel();


        $comment = $commentModel->find($id);
        if (!$comment) {
            return sendError("Comment not found.", ResponseInterface::HTTP_NOT_FOUND);
        }

        $newComment = $this->request->getPost('comment');
        if (empty($newComment)) {
            return sendError("Comment cannot be empty.", ResponseInterface::HTTP_BAD_REQUEST);
        }

        $data = [
            'comment' => $newComment,
        ];

        try {
            $commentModel->update($id, $data);
        } catch (\Exception $e) {
            return sendError("Something went wrong.", ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }

        return sendResponse($data, "Comment updated successfully.");
    }


    public function delete($id)
    {

        $session = session(); // No need to use Services::session() within a controller
        $userId = $session->get('userId');
        $commentModel = new CommentModel();
        $comment = $commentModel->find($id);
        if (!$comment) {
            return sendError("Comment not found.", ResponseInterface::HTTP_NOT_FOUND,);
        }

        if ($comment['user_id'] != $userId) {
            return sendError("You are not allowed to delete this comment.", ResponseInterface::HTTP_FORBIDDEN);
        }

        $commentModel->delete($id);

        return sendResponse($comment, "Comment deleted successfully.");
    }
}
