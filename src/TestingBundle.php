<?php
declare(strict_types=1);

namespace DosFarma\TestingBundle;

use DosFarma\TestingBundle\DependecyInjection\Compiler\GuzzleHttpApiCallsManagerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class TestingBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(
            new GuzzleHttpApiCallsManagerPass(),
        );
    }
}
