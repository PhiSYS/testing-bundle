<?php
declare(strict_types=1);

namespace PhiSYS\TestingBundle;

use PhiSYS\TestingBundle\DependecyInjection\Compiler\GuzzleHttpApiCallsManagerPass;
use PhiSYS\TestingBundle\DependecyInjection\Compiler\PostgresDbalDatabaseManagerPass;
use PhiSYS\TestingBundle\DependecyInjection\Compiler\RabbitMQManagerPass;
use PhiSYS\TestingBundle\DependecyInjection\Compiler\SymfonyMessengerBusManagerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class TestingBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(
                new GuzzleHttpApiCallsManagerPass(),
            )
            ->addCompilerPass(
                new RabbitMQManagerPass(),
            )
            ->addCompilerPass(
                new PostgresDbalDatabaseManagerPass(),
            )
            ->addCompilerPass(
                new SymfonyMessengerBusManagerPass(),
            )
        ;
    }
}
