<?php

namespace App\Serializer;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 *
 */
final class GroupsContextBuilder implements SerializerContextBuilderInterface
{
    /**
     * @var SerializerContextBuilderInterface
     */
    private SerializerContextBuilderInterface $decorated;
    /**
     * @var AuthorizationCheckerInterface
     */
    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * AdminGroupsContextBuilder constructor.
     *
     * @param SerializerContextBuilderInterface $decorated
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param Request $request
     * @param bool $normalization
     * @param array|null $extractedAttributes
     * @return array
     * @throws \ReflectionException
     */
    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {

        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $isAdmin = $this->authorizationChecker->isGranted('ROLE_ADMIN');

        if (isset($context['operation']) === false) {
            return $context;
        }
        $resourceClass = $context['resource_class'] ?? null;
        $classAlias = $context['operation']->getShortName() ?? strtolower(
            preg_replace(
                '/[A-Z][a-z]/',
                '_\\0',
                lcfirst(
                    (new \ReflectionClass($resourceClass))->getShortName()
                )
            )
        );
        $context['groups'] = $context['groups'] ?? [];
        $context['groups'][] = sprintf('%s:%s', $classAlias, $normalization ? 'read' : 'write');

        if (isset($context['groups']) && $isAdmin) {
            $context['groups'][] = $normalization ? 'admin:read' : 'admin:write';
            if ($resourceClass) {
                $context['groups'][] = sprintf('%s:admin:%s', $classAlias, $normalization ? 'read' : 'write');
            }
        }

        $context['groups'] = array_unique($context['groups']);

        return $context;
    }
}
