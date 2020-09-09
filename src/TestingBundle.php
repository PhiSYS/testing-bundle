<?php
declare(strict_types=1);

namespace DosFarma\TestingBundle;

use DosFarma\TestingBundle\DependecyInjection\Compiler\GuzzleHttpApiCallsManagerPass;
use DosFarma\TestingBundle\DependecyInjection\Compiler\PostgresDbalDatabaseManagerPass;
use DosFarma\TestingBundle\DependecyInjection\Compiler\RabbitMQManagerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class TestingBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(
                new GuzzleHttpApiCallsManagerPass()
            )
            ->addCompilerPass(
                new RabbitMQManagerPass()
            )
            ->addCompilerPass(
                new PostgresDbalDatabaseManagerPass()
            )
        ;
    }
}
