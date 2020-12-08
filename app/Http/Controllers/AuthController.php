<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Wallet;
use App\Utils\CommonResponse;
use LaravelDoctrine\ORM\Facades\EntityManager;

class AuthController
{
    /**
     * Login user and return the user if successful.
     *
     * @param string $email
     * @param string $password
     * @return \App\Utils\CommonResponse
     * @throws SoapFault
     */
    public function login($email, $password)
    {
        $credentials = array('email' => $email, 'password' => $password);

        if (!Auth::once($credentials)) {
            return new CommonResponse(401, 'Invalid credential');
        }

        return new CommonResponse(200, 'Login', auth()->user());
    }

    /**
     * Register a new user and return the user if successful.
     *
     * @param string $name
     * @param string $lastname
     * @param string $phone
     * @param string $email
     * @param string $password
     * @return \App\Utils\CommonResponse
     */
    public function register($name, $lastname, $phone, $document, $email, $password)
    {
        $user = new User($name, $lastname, $phone, $document, $email, $password);
        $user->setWallet(new Wallet(0));
        try {
            EntityManager::persist($user);
            EntityManager::flush();
        } catch (\Exception $exception){
            return new CommonResponse(400, 'Email already registered');
        }
      
        return new CommonResponse(200, 'Registered Successfuly', $user);
    }
}
