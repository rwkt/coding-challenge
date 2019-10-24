<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiAuthenticator extends AbstractGuardAuthenticator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    public function getCredentials(Request $request): array
    {
        return [
            'token' => $request->headers->get('X-AUTH-TOKEN'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $apiToken = $credentials['token'];
        if (null === $apiToken) {
            return null;
        }

        return $this->userRepository->findOneBy(['apiToken' => $apiToken]);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        return null;
    }

    public function start(Request $request, ?AuthenticationException $authException = null): JsonResponse
    {
        $data = [
            'message' => 'Authentication Required',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
