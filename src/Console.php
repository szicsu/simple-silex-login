<?php

namespace Login;

use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Console for Login.
 */
class Console extends ConsoleApplication
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        parent::__construct('Login');

        set_time_limit(0);

        $this->app = $app;
        $this->setDispatcher($app['dispatcher']);

        if( $this->app['debug'] ){
            $this->initDoctrine();
        }
    }

    private function initDoctrine()
    {
        $this->getHelperSet()->set(
            new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($this->app['orm.em']),
            'em'
        );

        $this->addCommands(array(
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
            new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
            new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
            new \Doctrine\ORM\Tools\Console\Command\InfoCommand(),
            new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
            new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
            new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),
            new \Doctrine\DBAL\Tools\Console\Command\ReservedWordsCommand(),
            new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
        ));
    }
}
