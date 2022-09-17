<?php

namespace Library\Session\Adapter;

use Phalcon\Db\{ Column, Enum };
use Phalcon\Db\Adapter\Pdo\Mysql as Connection;
use Phalcon\Logger\Logger;
use Phalcon\Session\Exception;

class Mysql implements \SessionHandlerInterface, \SessionUpdateTimestampHandlerInterface
{
    protected array $options = [];
    protected Connection $connection;
    protected ?Logger $logger;

    public function __construct(array $options = [])
    {
        if (! isset($options['connection'])) {
            throw new Exception('DB connection as an option must be set');
        }
        if (! $options['connection'] instanceof Connection) {
            throw new Exception(sprintf(
                'Used DB connection must be instance of %s', Connection::class
            ));
        }
        $this->connection = $options['connection'];
        $this->logger = $options['logger'] ?? null;
        $this->options = $options;
    }

    public function close(): bool
    {
        return true;
    }

    /**
     * @param string $sessionId
     * @return bool
     * @throws \Phalcon\Logger\Exception
     */
    public function destroy($sessionId): bool
    {
        if ($sessionId && $found = $this->getEntry($sessionId)) {
            return $this->catchableRun('DELETE FROM session_data WHERE id = ?', [$sessionId]);
        }
        return true;
    }

    /**
     * @param int $maxlifetime
     * @return bool
     * @throws \Phalcon\Logger\Exception
     */
    public function gc($maxlifetime): bool
    {
        $query = /** @lang sql */ 'DELETE FROM session_data WHERE COALESCE(modified_on, created_on) + ? < UNIX_TIMESTAMP()';
        return $this->catchableRun($query, [
            $this->options['lifetime'] ?? $maxlifetime ?? (int) ini_get('session.gc_maxlifetime')
        ]);
    }

    /**
     * @param string $savePath
     * @param string $name
     * @return bool
     */
    public function open($savePath, $name): bool
    {
        return true;
    }

    /**
     * @param string $sessionId
     * @return string
     */
    public function read($sessionId): string
    {
        $found = $this->getEntry($sessionId);
        return $found['data'] ?? '';
    }

    /**
     * @param string $sessionId
     * @param string $sessionData
     * @return bool
     */
    public function write($sessionId, $sessionData): bool
    {
        if (! $found = $this->getEntry($sessionId)) {
            $query = 'INSERT INTO session_data (id, data, created_on) VALUES (?, ?, UNIX_TIMESTAMP())';
            return $this->catchableRun($query, [$sessionId, $sessionData]);
        }
        $query = 'UPDATE session_data SET data = ?, modified_on = UNIX_TIMESTAMP() WHERE id = ?';
        return $this->catchableRun($query, [$sessionData, $sessionId]);
    }

    /**
     * @param string $sessionId
     * @return bool
     */
    public function validateId($sessionId): bool
    {
        return strlen($sessionId) === (int) ini_get('session.sid_length');
    }

    /**
     * Called if the dataset is not modified
     * @see https://wiki.php.net/rfc/session-read_only-lazy_write
     * @param $sessionId
     * @param $sessionData
     * @return bool
     * @throws \Phalcon\Logger\Exception
     */
    public function updateTimestamp($sessionId, $sessionData): bool
    {
        if (! $found = $this->getEntry($sessionId)) {
            return true;
        }
        $delay = isset($this->options['ignoring_delay']) ? (int) $this->options['ignoring_delay'] : 0;
        if (! $delay || (time() - (int) $found['modified_on']) > $delay) {
            $query = 'UPDATE session_data SET modified_on = UNIX_TIMESTAMP() WHERE id = ?';
            return $this->catchableRun($query, [$sessionId]);
        }
        return true;
    }

    /**
     * @param string $id
     * @return array
     */
    private function getEntry(string $id): array
    {
        $query = 'SELECT * FROM session_data WHERE id = ?';
        return $this->connection->fetchOne($query, Enum::FETCH_ASSOC, [$id], [Column::BIND_PARAM_STR]) ?: [];
    }

    /**
     * @param string $query
     * @param array $params
     * @return bool
     * @throws \Phalcon\Logger\Exception
     */
    private function catchableRun(string $query, array $params = []): bool
    {
        try {
            return $this->connection->execute($query, $params);
        } catch (\Exception $e) {
            if ($this->logger instanceof Logger) {
                $this->logger->error(sprintf('[SESSION] %s', $e->getMessage()));
            }
        }
        return true;
    }
}
