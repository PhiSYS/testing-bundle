<?php
declare(strict_types=1);

namespace DosFarma\TestingBundle\DependecyInjection\Compiler;

use DosFarma\Testing\Behaviour\SymfonyMessenger\SymfonyMessengerConsumeManager;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class SymfonyMessengerConsumeManagerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(Application::class)) {
            throw new \Exception(
                sprintf('Must have %s definition in Symfony Service Container', Application::class),
            );
        }

        $messageConsumeManagerDefinition = new Definition(
            SymfonyMessengerConsumeManager::class,
            [
                new Reference(Application::class),
                'testApp',
                'v0.1',
            ],
        );

        $container->addDefinitions(
            [
                SymfonyMessengerConsumeManager::class => $messageConsumeManagerDefinition->setPublic(true),
            ],
        );
    }
}
