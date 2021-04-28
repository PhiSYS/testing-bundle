<?php
declare(strict_types=1);

namespace PhiSYS\TestingBundle\DependecyInjection\Compiler;

use PhiSYS\Testing\Behaviour\AMQP\AmqpConnectionFactory;
use PhiSYS\Testing\Behaviour\AMQP\RabbitMQManager;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class RabbitMQManagerPass implements CompilerPassInterface
{
    private const TESTING_HOST_PARAMETER_NAME = 'dos_farma.testing.acceptance_tests.rabbitmq.host';

    public function process(ContainerBuilder $container)
    {
        $dsn = $container->getParameter(self::TESTING_HOST_PARAMETER_NAME);

        $AmqpConnection = new Definition(
            AMQPStreamConnection::class,
            [
                $dsn,
            ],
        );
        $AmqpConnection->setFactory(AmqpConnectionFactory::class . '::fromDsn');

        $rabbitMQManagerDefinition = new Definition(
            RabbitMQManager::class,
            [
                $AmqpConnection,
            ],
        );

        $container->addDefinitions(
            [
                RabbitMQManager::class => $rabbitMQManagerDefinition->setPublic(true),
            ],
        );
    }
}
