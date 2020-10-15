<?php
declare(strict_types=1);

namespace DosFarma\TestingBundle\DependecyInjection\Compiler;

use DosFarma\Testing\Behaviour\Bus\SymfonyMessengerBusManager;
use http\Env\Response;
use PcComponentes\Ddd\Util\Message\Serialization\JsonApi\AggregateMessageStreamDeserializer;
use PcComponentes\Ddd\Util\Message\Serialization\JsonApi\SimpleMessageStreamDeserializer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class SymfonyMessengerBusManagerPass implements CompilerPassInterface
{

    private const PUBLISH_EVENT_BUS = 'publish_event.bus';
    private const EXECUTE_EVENT_BUS = 'execute_event.bus';
    private const PUBLISH_COMMAND_BUS = 'publish_command.bus';
    private const EXECUTE_COMMAND_BUS = 'execute_command.bus';

    public function process(ContainerBuilder $container)
    {
        $this->assertDependencies($container);

        $busManagerDefinition = new Definition(
            SymfonyMessengerBusManager::class,
            [
                new Reference(self::PUBLISH_EVENT_BUS),
                new Reference(self::EXECUTE_EVENT_BUS),
                new Reference(self::PUBLISH_COMMAND_BUS),
                new Reference(self::EXECUTE_COMMAND_BUS),
                new Reference(AggregateMessageStreamDeserializer::class),
                new Reference(SimpleMessageStreamDeserializer::class),
            ]
        );

        $container->addDefinitions(
            [
                SymfonyMessengerBusManager::class => $busManagerDefinition->setPublic(true),
            ],
        );
    }

    private function assertDependencies(ContainerBuilder $container): void
    {
        if (false === $container->hasAlias(self::PUBLISH_EVENT_BUS)) {
            throw new \Exception(
                \sprintf('Must have %s definition in Symfony Service Container', self::PUBLISH_EVENT_BUS),
            );
        }

        if (false === $container->hasAlias(self::EXECUTE_EVENT_BUS)) {
            throw new \Exception(
                \sprintf('Must have %s definition in Symfony Service Container', self::EXECUTE_EVENT_BUS),
            );
        }

        if (false === $container->hasAlias(self::PUBLISH_COMMAND_BUS)) {
            throw new \Exception(
                \sprintf('Must have %s definition in Symfony Service Container', self::PUBLISH_COMMAND_BUS),
            );
        }

        if (false === $container->hasAlias(self::EXECUTE_COMMAND_BUS)) {
            throw new \Exception(
                \sprintf('Must have %s definition in Symfony Service Container', self::EXECUTE_COMMAND_BUS),
            );
        }

        if (false === $container->hasDefinition(AggregateMessageStreamDeserializer::class)) {
            throw new \Exception(
                \sprintf('Must have %s definition in Symfony Service Container', AggregateMessageStreamDeserializer::class),
            );
        }

        if (false === $container->hasDefinition(SimpleMessageStreamDeserializer::class)) {
            throw new \Exception(
                \sprintf('Must have %s definition in Symfony Service Container', SimpleMessageStreamDeserializer::class),
            );
        }
    }
}