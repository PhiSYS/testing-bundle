<?php
declare(strict_types=1);

namespace DosFarma\TestingBundle\DependecyInjection\Compiler;

use DosFarma\Testing\Behaviour\AMQP\Connection;
use DosFarma\Testing\Behaviour\AMQP\RabbitMQManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class RabbitMQManagerPass implements CompilerPassInterface
{
    private const TESTING_HOST_PARAMETER_NAME = 'dos_farma.testing.acceptance_test.rabbitmq.host';

    public function process(ContainerBuilder $container)
    {

        if (false === $container->hasDefinition(Connection::class)) {
            throw new \Exception(
                \sprintf('Must have %s definition in Symfony Service Container', Connection::class)
            );
        }

        $dsn = $container->getParameter(self::TESTING_HOST_PARAMETER_NAME);

        $rabbitMQManagerDefinition = new Definition(
            RabbitMQManager::class,
            [
                new Reference(Connection::class),
                $dsn,
            ],
        );

        $container->addDefinitions(
            [
                RabbitMQManager::class => $rabbitMQManagerDefinition->setPublic(true),
            ],
        );
    }
}
