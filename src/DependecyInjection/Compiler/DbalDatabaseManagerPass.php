<?php
declare(strict_types=1);

namespace PhiSYS\TestingBundle\DependecyInjection\Compiler;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PhiSYS\Testing\Behaviour\Database\Dbal\MysqlDbalDatabaseManager;
use PhiSYS\Testing\Behaviour\Database\Dbal\PostgresDbalDatabaseManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class DbalDatabaseManagerPass implements CompilerPassInterface
{
    private const TESTING_DATABASE_DSN = 'phisys.testing_bundle.acceptance_tests.db.dsn';

    public function process(ContainerBuilder $container)
    {
        $connection = new Definition(
            Connection::class,
            [
                ['url' => $container->getParameter(self::TESTING_DATABASE_DSN)],
            ],
        );

        $connection->setFactory(DriverManager::class . '::getConnection');

        $databaseManagerClasses = [
            MysqlDbalDatabaseManager::class,
            PostgresDbalDatabaseManager::class,
        ];

        foreach($databaseManagerClasses as $databaseManagerClass) {
            $databaseManagerDefinition = new Definition(
                $databaseManagerClass,
                [
                    $connection,
                ],
            );

            $container->addDefinitions(
                [$databaseManagerClass => $databaseManagerDefinition->setPublic(true)],
            );
        }
    }
}
