<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\DTO\TeamChoiceSubscriptionDto;
use App\Entity\Team;
use App\Helper\TeamHelper;
use App\Service\StripeService;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Security\Core\Security;

/**
 * Class TeamChoiceSubscriptionController
 * @package App\Controller
 */
#[AsController]
class TeamChoiceSubscriptionController extends AbstractController
{

    /**
     * @param Security $security
     * @param StripeService $stripeService
     */
    public function __construct(
        private readonly Security $security,
        private readonly StripeService $stripeService
    ) {
    }

    /**
     * @param Team $team
     * @param TeamChoiceSubscriptionDto $data
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function __invoke(Team $team, #[MapRequestPayload] TeamChoiceSubscriptionDto $data): JsonResponse
    {
        TeamHelper::checkIfUserIsTeamManager($this->security->getUser(), $team);
        TeamHelper::checkIfTeamHasAlreadySubscription($team);

        return $this->json($this->stripeService->startSession($this->security->getUser(), $team, $data->getSubscriptionPrice()));
    }
}
