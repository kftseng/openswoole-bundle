<?php

declare(strict_types=1);

namespace K911\Swoole\Bridge\Doctrine\ORM;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception as DBALException;
use K911\Swoole\Server\RequestHandler\RequestHandlerInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class EntityManagerHandler implements RequestHandlerInterface
{
    private $decorated;
    private $connection;
    private $entityManager;

    public function __construct(RequestHandlerInterface $decorated, EntityManagerInterface $entityManager)
    {
        $this->decorated = $decorated;
        $this->entityManager = $entityManager;
        $this->connection = $entityManager->getConnection();
    }

    private function ping()
    {
        try {
            $this->connection->query($this->connection->getDatabasePlatform()->getDummySelectSQL());
            return true;
        } catch (DBALException $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, Response $response): void
    {
        if (!$this->ping()) {
            $this->connection->close();
            $this->connection->connect();
        }

        $this->decorated->handle($request, $response);

        $this->entityManager->clear();
    }
}

 