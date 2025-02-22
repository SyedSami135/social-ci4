<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Firebase\JWT\JWT;

use function App\Helpers\createJWT;
use function App\Helpers\verifyPassword;

class UserController extends BaseController
{


    public function registerForm()
    {
        return view('auth/register');
    }
    public function loginForm()
    {
        return view('auth/login');
    }

    public function register()
    {
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Basic validation
        if (empty($username) || empty($email) || empty($password)) {
            return  sendError(400, 'All fields are required.');
        }

        // Check if email already exists
        $userModel = new UserModel();
        if ($userModel->emailExists($email)) {
            return sendError(409, 'Email already exists.');
        }

        // Prepare data for insertion
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ];

        // Insert the new user into the database
        $result = $userModel->insert($userData);

        if ($result) {
            // Return success response
            return redirect()->to('/users/login')->with('message', 'User registered successfully.');
        } else {
            // Error response
            return redirect()->to('/users/login')->with('message', 'User registration failed.');
        }
    }
    public function login()
    {
        // Get email and password from POST request
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validate the request body
        if (empty($email) || empty($password)) {
            return sendError(400, 'All fields are required.');
        }

        // Check if email exists
        $userModel = new UserModel();
        $user = $userModel->getUserByEmail($email);

        if (!$user) {
            return sendError(404, 'User not found.');
        }

        // Generate JWT token
        $token = createJWT($user['id']);

        // Verify the password
        if (!verifyPassword($password, $user['password'])) {
            return sendError(401, 'Invalid password.');
        }
        // Remove the password from the user object
        unset($user['password']);

        // Return the success response with the token
        return $this->response->setStatusCode(200, 'OK')
            ->setCookie('token', $token, 3600 * 4)
            ->setHeader('Authorization', 'Bearer ' . $token)
            ->setJSON([
                'message' => 'Login successful.',
                'user' => $user,
                'token' => $token,
            ]);
    }
}
