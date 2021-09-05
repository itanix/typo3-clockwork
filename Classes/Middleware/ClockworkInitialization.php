<?php
namespace Itanix\Clockwork\Middleware;

use Clockwork\DataSource\DBALDataSource;
use Clockwork\Support\Vanilla\Clockwork;
use Itanix\Clockwork\DataSource\TYPO3DataSource;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ClockworkInitialization implements MiddlewareInterface
{
    protected Clockwork $clockwork;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $this->clockwork = Clockwork::init([
            'enable' => $GLOBALS['TYPO3_CONF_VARS']['FE']['debug'] ?? false,

            'features' => [
                'performance' => [
                    'client_metrics' => false
                ]
            ],

            'toolbar' => false,

            'requests' => [
                'on_demand' => false,
                'errors_only' => false,
                'slow_threshold' => null,
                'slow_only' => false,
                'sample' => false,
                'except' => [],
                'only' => [],
                'except_preflight' => true
            ],

            'api' => '/__clockwork?request=',

            'web' => [
                'enable' => false,
                'path' => '',
                'uri' => ''
            ],

            'storage' => 'files',
            'storage_files_path' => GeneralUtility::getFileAbsFileName('typo3temp/clockwork'),
            'storage_files_compress' => false,
            'storage_expiration' => 60 * 24 * 7,

            'stack_traces' => [
                'enabled' => true,
                'limit' => 10,
                'skip_vendors' => [],
                'skip_namespaces' => [],
                'skip_classes' => []
            ],

            'serialization_depth' => 3,
            'serialization_blackbox' => [],
        ]);

        $this->clockwork->addDataSource(new TYPO3DataSource);
        $this->initializeDatabaseSource();

        return $handler->handle($request);
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function initializeDatabaseSource()
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);

        foreach($connectionPool->getConnectionNames() as $connectionName)
        {
            $connection = $connectionPool->getConnectionByName($connectionName);

            $dataSource = new DBALDataSource($connection);
            $this->clockwork->addDataSource($dataSource);
        }
    }
}