<?php
namespace App\Controller\API;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/api/users/me', name: 'api_users_me', methods: ['GET'])]
    public function me(){

        return $this->json($this->getUser(), 200, [],[
            'groups' => ['users.me']
        ]);
    }
}