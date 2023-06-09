<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CurrentUserProvider implements ProviderInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage
    )
    {}
    
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if($this->tokenStorage->getToken()){
            $user = $this->tokenStorage->getToken()->getUser();
        return $user;
        } else {
            return null;
        }
    }
}
