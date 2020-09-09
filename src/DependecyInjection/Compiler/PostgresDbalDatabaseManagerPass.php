<?php
declare(strict_types=1);

namespace DosFarma\TestingBundle\DependecyInjection\Compiler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use DosFarma\Testing\Behaviour\Database\Dbal\PostgresDbalDatabaseManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class PostgresDbalDatabaseManagerPass implements CompilerPassInterface
{
    private const TESTING_DATABASE_DSN = 'dos_farma.testing.acceptance_tests.db.dsn';

    public function process(ContainerBuilder $container)
    {
        $connection = new Definition(
            Connection::class,
            [
                ['url' => $container->getParameter(self::TESTING_DATABASE_DSN)],
            ],
        );

        $connection->setFactory( DriverManager::class . '::getConnection');

        $databaseManagerDefinition = new Definition(
            PostgresDbalDatabaseManager::class,
            [
                $connection,
            ],
        );

        $container->addDefinitions(
            [PostgresDbalDatabaseManager::class => $databaseManagerDefinition->setPublic(true)],
        );
    }
}
