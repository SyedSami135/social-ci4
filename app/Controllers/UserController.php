<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Firebase\JWT\JWT;

use function App\Helpers\createJWT;
use function App\Helpers\verifyPassword;

class UserController extends BaseController
{
    private $secretKey = 'your_secret_key';
    public function register()
    {
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Basic validation
        if (empty($username) || empty($email) || empty($password)) {
            return $this->response->setStatusCode(400, 'Bad Request')
                ->setJSON(['error' => 'All fields are required.']);
        }

        // Check if email already exists
        $userModel = new UserModel();
        if ($userModel->emailExists($email)) {
            return $this->response->setStatusCode(400, 'Bad Request')
                ->setJSON(['error' => 'Email is already taken.']);
        }

        // Prepare data for insertion
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,  // Password will be hashed automatically
        ];

        // Insert the new user into the database
        $result = $userModel->insert($userData);

        if ($result) {
            // Return success response
            return $this->response->setStatusCode(201, 'Created')
                ->setJSON(['message' => 'User registered successfully.']);
        } else {
            // Error response
            return $this->response->setStatusCode(500, 'Internal Server Error')
                ->setJSON(['error' => 'Failed to register user.']);
        }
    }
    public function login()
    {
        // Get email and password from POST request
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');




        // Validate the request body
        if (empty($email) || empty($password)) {
            return $this->response->setStatusCode(400, 'Bad Request')
                ->setJSON(['error' => 'Both email and password are required.']);
        }

        // Check if email exists
        $userModel = new UserModel();

        $user = $userModel->getUserByEmail($email);

        if (!$user) {
            return $this->response->setStatusCode(401, 'Unauthorized')
                ->setJSON(['error' => 'Invalid email or password.']);
        }

        echo $user['id'];

        
        $iat = time(); // current timestamp value
        $exp = $iat + 3600*24; // 1 day expiration
 
        $payload = array(
            "iss" => "Issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat, //Time the JWT issued at
            "exp" => $exp, // Expiration time of token
            "id" => $user['id'],
        );
         
        $token = JWT::encode($payload, $this->secretKey, 'HS256');

        unset($user['password']);

        return $this->response->setStatusCode(200, 'OK')
        ->setJSON([
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $token,
        ]);


        // Verify the password
        if (!verifyPassword($password, $user->password)) {
            return $this->response->setStatusCode(401, 'Unauthorized')
                ->setJSON(['error' => 'Invalid email or password.']);
        }

        try {
            $jwt =  createJWT($user->id, $this->secretKey);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500, 'Internal Server Error')
                ->setJSON(['error' => 'Could not generate JWT token.', 'message' => $e->getMessage()]);
        }

        // Return the success response with the token
        return $this->response->setStatusCode(200, 'OK')
            ->setJSON([
                'message' => 'Login successful.',
                'token' => $jwt,
            ]);
    }
}
