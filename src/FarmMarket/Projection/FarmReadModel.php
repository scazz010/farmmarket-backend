<?php
/**
 * This file is part of prooph/proophessor-do.
 * (c) 2014-2017 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2017 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);
namespace App\FarmMarket\Projection;

use App\Entity\Farm;
use App\Geo\Point;
use App\Repository\FarmRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Prooph\EventStore\Projection\AbstractReadModel;
use App\FarmMarket\Projection\Table;

final class FarmReadModel extends AbstractReadModel
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var FarmRepository
     */
    private $farmRepository;
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(FarmRepository $farmRepository, EntityManagerInterface $em, Connection $connection)
    {
        $this->connection = $connection;
        $this->farmRepository = $farmRepository;
        $this->em = $em;
    }
    public function init(): void
    {
        $tableName = Table::FARM;
        $sql = <<<EOT
CREATE TABLE `$tableName` (
  `id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `open_todos` int(11) NOT NULL DEFAULT '0',
  `done_todos` int(11) NOT NULL DEFAULT '0',
  `expired_todos` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
EOT;
        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }
    public function isInitialized(): bool
    {
        $tableName = Table::FARM;
        $sql = "SHOW TABLES LIKE '$tableName';";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetch();
        if (false === $result) {
            return false;
        }
        return true;
    }
    public function reset(): void
    {
        $tableName = Table::FARM;
        $sql = "TRUNCATE TABLE $tableName;";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }
    public function delete(): void
    {
        $tableName = Table::FARM;
        $sql = "DROP TABLE $tableName;";
        $statement = $this->connection->prepare($sql);
        $statement->execute();
    }
    protected function insert(array $data): void
    {
        $this->connection->insert(Table::FARM, $data);
    }
    protected function updateLocation(string $farmId, Point $location): void
    {
        /** @var Farm $farm */
        $farm = $this->farmRepository->find($farmId);
        $farm->setLocation($location);
        $this->em->flush($farm);

        $stmt = $this->connection->prepare(sprintf('UPDATE %s SET location = POINT(:longitude, :latitude) WHERE id = :farm_id', Table::FARM));
        $stmt->bindValue('farm_id', $farmId);
        $stmt->bindValue('longitude', $location->getLong());
        $stmt->bindValue('latitude', $location->getLat());
        $stmt->execute();
    }
    protected function markTodoAsDone(string $assigneeId): void
    {
        $stmt = $this->connection->prepare(sprintf('UPDATE %s SET open_todos = open_todos - 1, done_todos = done_todos + 1 WHERE id = :assignee_id', Table::USER));
        $stmt->bindValue('assignee_id', $assigneeId);
        $stmt->execute();
    }
    protected function reopenTodo(string $assigneeId): void
    {
        $stmt = $this->connection->prepare(sprintf('UPDATE %s SET open_todos = open_todos + 1, done_todos = done_todos - 1 WHERE id = :assignee_id', Table::USER));
        $stmt->bindValue('assignee_id', $assigneeId);
        $stmt->execute();
    }
    protected function markTodoAsExpired(string $assigneeId): void
    {
        $stmt = $this->connection->prepare(sprintf('UPDATE %s SET open_todos = open_todos - 1, expired_todos = expired_todos + 1 WHERE id = :assignee_id', Table::USER));
        $stmt->bindValue('assignee_id', $assigneeId);
        $stmt->execute();
    }
    protected function unmarkedTodoAsExpired(string $assigneeId): void
    {
        $stmt = $this->connection->prepare(sprintf('UPDATE %s SET open_todos = open_todos + 1, expired_todos = expired_todos - 1 WHERE id = :assignee_id', Table::USER));
        $stmt->bindValue('assignee_id', $assigneeId);
        $stmt->execute();
    }
}