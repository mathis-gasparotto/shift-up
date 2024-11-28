<?php

namespace App\Controller;

use App\Service\StripeService;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * class StripeController
 * package App\Controller
 */
#[AsController]
class StripeController extends AbstractController
{
    /**
     * @param StripeService $stripeService
     * @return RedirectResponse|Response
     * @throws ApiErrorException
     * @throws \Exception
     */
    #[
        Route(
            path: '/webhook/confirmation_stripe_payment',
            name: 'app_confirmation_stripe_payment',
            methods: ['POST'],
        )
    ]
    public function confirmationStripePayment(StripeService $stripeService): RedirectResponse | Response
    {
        return $stripeService->confirmationPayment();
    }
}