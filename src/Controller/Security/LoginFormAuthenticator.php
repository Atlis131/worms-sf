<?php

namespace App\Controller\Security;

use App\Repository\User\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private string          $login_route;
    private UserRepository  $userRepository;
    private RouterInterface $router;

    public function __construct(
        UserRepository  $userRepository,
        RouterInterface $router
    )
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function supports(Request $request): bool
    {
        $this->login_route = $request->attributes->get('_route');

        return ($request->attributes->get('_route') === 'login')
            && $request->isMethod('POST');
    }

    public function onAuthenticationSuccess(
        Request        $request,
        TokenInterface $token,
                       $firewallName
    ): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate('homepage'));
    }

    protected function getLoginUrl(
        Request $request
    ): string
    {
        return $this->router->generate('login');
    }

    public function authenticate(
        Request $request
    ): Passport
    {
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');
        $csrfToken = $request->request->get('_csrf_token');

        return new Passport(
            new UserBadge($username, function ($userIdentifier) {
                $user = $this->userRepository->findOneByUsernameOrEmail($userIdentifier);
                if (!$user) {
                    throw new UserNotFoundException();
                }

                if (!$user->getEmailVerificationDate()) {
                    throw new UserNotFoundException();
                }

                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
                (new RememberMeBadge())->enable()
            ]
        );

    }
}
