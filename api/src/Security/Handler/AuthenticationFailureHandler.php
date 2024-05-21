<?php

declare(strict_types=1);

namespace App\Security\Handler;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as ContractsEventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 *
 */
class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected EventDispatcherInterface $dispatcher;

    /**
     * @var TranslatorInterface
     */
    protected TranslatorInterface $translator;

    /**
     * AuthenticationFailureHandler constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param TranslatorInterface      $translator
     */
    public function __construct(EventDispatcherInterface $dispatcher, TranslatorInterface $translator)
    {
        $this->dispatcher = $dispatcher;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {

        $message = 'Invalid credentials';
        if ($exception instanceof AccountExpiredException) {
            $message = $exception->getMessageKey();
        } elseif ($exception instanceof LockedException) {
            $message = $exception->getMessageKey();
        } elseif ($exception instanceof DisabledException) {
            $message = $exception->getMessageKey();
        }

        $response = new JWTAuthenticationFailureResponse($this->translator->trans($message, [], 'security'));
        $event = new AuthenticationFailureEvent($exception, $response);

        if ($this->dispatcher instanceof ContractsEventDispatcherInterface) {
            $this->dispatcher->dispatch($event, Events::AUTHENTICATION_FAILURE);
        } else {
            $this->dispatcher->dispatch((object)Events::AUTHENTICATION_FAILURE, $event);
        }

        return $event->getResponse();
    }
}
