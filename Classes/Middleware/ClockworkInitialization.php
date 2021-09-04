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
            'api' => '/__clockwork?request=',
            'storage_files_path' => GeneralUtility::getFileAbsFileName('typo3temp/clockwork'),
            'toolbar' => false
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