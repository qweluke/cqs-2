<?php

namespace AppBundle\Handler;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * Class AuthenticationHandler
 * @package AppBundle\Handler
 */
class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var Router
     */
    private $router;

    /**
     * AuthenticationHandler constructor.
     * @param Session $session
     * @param Translator $translator
     * @param TokenStorage $tokenStorage
     * @param Router $router
     */
    public function __construct(
        Session $session,
        Translator $translator,
        TokenStorage $tokenStorage,
        Router $router
    )
    {
        $this->session = $session;
        $this->translator = $translator;
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    /**
     * @return Session
     */
    public function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @return Translator
     */
    public function getTranslator(): Translator
    {
        return $this->translator;
    }

    /**
     * @return TokenStorage
     */
    public function getTokenStorage(): TokenStorage
    {
        return $this->tokenStorage;
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return JsonResponse|RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($request->isXmlHttpRequest()) {
            if($token->getUser()->getIsEnabled() === false) {

                $request->getSession()->invalidate();
                $this->getTokenStorage()->setToken(null);

                return new JsonResponse([
                    'success' => false,
                    'error'   => 'form_error',
                    'message' => $this->getTranslator()->trans('sign_in.account_disabled')
                ], Response::HTTP_FORBIDDEN);

            } else {
                return new JsonResponse([
                    'success' => true,
                    'message' => $this->getTranslator()->trans('sign_in.success')
                ]);
            }
        } else {
            return new RedirectResponse($this->getRouter()->generate('homepage'));
        }
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|RedirectResponse
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse([
                'success' => false,
                'error'   => 'form_error',
                'message' => $this->getTranslator()->trans($exception->getMessageKey())
            ], Response::HTTP_BAD_REQUEST);
        } else {
            return new RedirectResponse($this->getRouter()->generate('homepage'));
        }
    }
}