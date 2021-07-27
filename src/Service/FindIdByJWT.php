<?php

namespace App\Service;

use App\Repository\UserRepository;

class FindIdByJWT
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function find($jwt)
    {
        $jwtArray = explode('.', $jwt);
        $tokenPayload = base64_decode($jwtArray[1]);
        $jwtPayload = json_decode($tokenPayload);
        $username = $jwtPayload->username;
        $userId = $this->userRepository->findIdByEmail($username);
        return $userId[0]['id'];
    }
}