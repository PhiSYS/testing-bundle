<?php
declare(strict_types=1);

namespace DosFarma\TestingBundle\DependecyInjection\Compiler;

use DosFarma\Testing\Behaviour\Bus\SymfonyMessengerBusManager;
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
    private const AGGREGATE_MESSAGE_DESERIALIZER = 'pccom.messenger_bundle.aggregate_message.serializer.stream_deserializer';
    private const SIMPLE_MESSAGE_DESERIALIZER = 'pccom.messenger_bundle.simple_message.serializer.stream_deserializer';

    public function process(ContainerBuilder $container)
    {
        $busManagerDefinition = new Definition(
            SymfonyMessengerBusManager::class,
            [
                new Reference(self::PUBLISH_EVENT_BUS),
                new Reference(self::EXECUTE_EVENT_BUS),
                new Reference(self::PUBLISH_COMMAND_BUS),
                new Reference(self::EXECUTE_COMMAND_BUS),
                new Reference(self::AGGREGATE_MESSAGE_DESERIALIZER),
                new Reference(self::SIMPLE_MESSAGE_DESERIALIZER),
            ],
        );

        $container->addDefinitions(
            [
                SymfonyMessengerBusManager::class => $busManagerDefinition->setPublic(true),
            ],
        );
    }
}