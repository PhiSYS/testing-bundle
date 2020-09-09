<?php
declare(strict_types=1);

namespace DosFarma\TestingBundle\DependecyInjection\Compiler;

use DosFarma\Testing\Behaviour\ApiRest\GuzzleHttpApiCallsManager;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class GuzzleHttpApiCallsManagerPass implements CompilerPassInterface
{
    private const TESTING_HOST_PARAMETER_NAME = 'dos_farma.testing.acceptance_tests.api.host';

    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(Client::class)) {
            throw new \Exception(
                \sprintf('Must have %s definition in Symfony Service Container', Client::class),
            );
        }

        $host = $container->getParameter(self::TESTING_HOST_PARAMETER_NAME);

        $apiCallsManagerDefinition = new Definition(
            GuzzleHttpApiCallsManager::class,
            [
                new Reference(Client::class),
                $host,
            ],
        );

        $container->addDefinitions(
            [
                GuzzleHttpApiCallsManager::class => $apiCallsManagerDefinition->setPublic(true),
            ],
        );
    }
}
