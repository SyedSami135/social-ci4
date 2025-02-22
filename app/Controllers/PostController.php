<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostModel;

class PostController extends BaseController
{
    public function index()
    {
        $session = session(); // No need to use Services::session() within a controller
        $userId = $session->get('userId');

        $postModel = new PostModel();
        $posts = $postModel->findByUserId($userId);

    
        return $this->response->setStatusCode(200)
            ->setJSON(['data' => $posts]);
    }


    public function create()
    {
        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
        $session = session(); // No need to use Services::session() within a controller
        $userId = $session->get('userId');


        // Validate input data
        if (empty($title) || empty($content)) {
            return $this->response->setStatusCode(400, 'Bad Request')
                ->setJSON(['error' => 'All fields are required.']);
        }



        $post = new PostModel();
        $postData = [
            'title' => $title,
            'content' => $content,
            'user_id' => $userId,
        ];


        $result = $post->insert($postData);

        if ($result) {
            // Return success response
            return $this->response->setStatusCode(201)
                ->setJSON([
                    'message' => 'Post created successfully',
                    'data' => [
                        ...$postData,
                        'post_id' => $post->insertID(),  // Returning the ID of the newly created post

                    ]
                ]);
        } else {
            // Error response
            return $this->response->setStatusCode(500, 'Internal Server Error')
                ->setJSON(['error' => 'Failed to create post.']);
        }
    }

    public function delete($id)
    {


        $post = new PostModel();
        $session = session(); // No need to use Services::session() within a controller
        $userId = $session->get('userId');


        if (!$post->findById($id)) {
            return $this->response->setStatusCode(404, 'Not Found')
                ->setJSON(['error' => 'Post not found']);
        }

        if($post->findById($id)['user_id'] != $userId){
            return $this->response->setStatusCode(403, 'Forbidden')
                ->setJSON(['error' => 'You are not allowed to delete this post']);
        }
        
        $post->delete($id);

        return $this->response->setStatusCode(200)
            ->setJSON(['message' => 'Post deleted successfully']);
    }


    public function update($id)
    {
        $title = $this->request->getPost('title');
        $content = $this->request->getPost('content');
    
        if (empty($title) && empty($content)) {

            echo $title;
            echo "title";
            echo $content;
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => 'At least one field (title or content) must be provided.']);
        }
    
        $postModel = new PostModel();
        $postData = $postModel->find($id);
    
        if (!$postData) {
            return $this->response->setStatusCode(404, 'Not Found')->setJSON(['error' => 'Post not found']);
        }
    
        // Merge updated fields with existing data
        $updateData = [];
        if (!empty($title)) {
            $updateData['title'] = $title;
        }
        if (!empty($content)) {
            $updateData['content'] = $content;
        }
    
        // Perform the update
        if ($postModel->update($id, $updateData)) {
            return $this->response->setStatusCode(200)->setJSON([
                'message' => 'Post updated successfully.',
                'data' => [
                    'post_id' => $id,
                    'title'   => $title?? $postData['title'],
                    'content' => $content ?? $postData['content'],
                ]
            ]);
        } else {
            return $this->response->setStatusCode(500, 'Internal Server Error')
                ->setJSON(['error' => 'Failed to update post.']);
        }
    }
    
    public function read($id)
    {
        $postModel = new PostModel();
        $postData = $postModel->find($id);
    
        if (!$postData) {
            return $this->response->setStatusCode(404, 'Not Found')->setJSON(['error' => 'Post not found']);
        }
    
        return $this->response->setStatusCode(200)->setJSON(['data' => $postData]);
    }


}
