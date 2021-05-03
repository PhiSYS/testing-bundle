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
        $dsn = $container->getParameter(self::TESTING_DATABASE_DSN);

        $databaseManagerClass = $this->getDatabaseManagerClass($dsn);

        $connection = new Definition(
            Connection::class,
            [
                ['url' => $dsn],
            ],
        );

        $connection->setFactory(DriverManager::class . '::getConnection');

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

    private function getDatabaseManagerClass($dsn): string
    {
        if (1 !== preg_match('/^([^:]):/', $dsn, $matches)) {
            throw new \InvalidArgumentException("Invalid DSN format");
        }

        switch ($matches[1]) {
            case 'mysql':
                $databaseManagerClass = MysqlDbalDatabaseManager::class;
                break;
            case 'postgresql':
                $databaseManagerClass = PostgresDbalDatabaseManager::class;
                break;
            default:
                throw new \InvalidArgumentException('DSN not supported by phisys/testing library');
        }

        return $databaseManagerClass;
    }
}
