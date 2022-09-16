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
     * @param string $id
     * @return bool
     * @throws \Phalcon\Logger\Exception
     */
    public function destroy(string $id): bool
    {
        if ($id && $this->getEntry($id)) {
            return $this->catchableRun('DELETE FROM session_data WHERE id = ?', [$id]);
        }
        return true;
    }

    /**
     * @param int $max_lifetime
     * @return bool
     * @throws \Phalcon\Logger\Exception
     */
    public function gc(int $max_lifetime): bool
    {
        $query = /** @lang sql */ 'DELETE FROM session_data WHERE COALESCE(modified_on, created_on) + ? < UNIX_TIMESTAMP()';
        return $this->catchableRun($query, [
            $this->options['lifetime'] ?? $max_lifetime ?? (int) ini_get('session.gc_maxlifetime')
        ]);
    }

    /**
     * @param string $path
     * @param string $name
     * @return bool
     */
    public function open(string $path, string $name): bool
    {
        return true;
    }

    /**
     * @param string $id
     * @return string
     */
    public function read(string $id): string
    {
        $found = $this->getEntry($id);
        return $found['data'] ?? '';
    }

    /**
     * @param string $id
     * @param string $data
     * @return bool
     * @throws \Phalcon\Logger\Exception
     */
    public function write(string $id, string $data): bool
    {
        if (! $this->getEntry($id)) {
            $query = 'INSERT INTO session_data (id, data, created_on) VALUES (?, ?, UNIX_TIMESTAMP())';
            return $this->catchableRun($query, [$id, $data ?: null]);
        }
        $query = 'UPDATE session_data SET data = ?, modified_on = UNIX_TIMESTAMP() WHERE id = ?';
        return $this->catchableRun($query, [$data ?: null, $id]);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function validateId(string $id): bool
    {
        return strlen($id) === (int) ini_get('session.sid_length');
    }

    /**
     * Called if the dataset is not modified
     * @see https://wiki.php.net/rfc/session-read_only-lazy_write
     * @param string $id
     * @param string $data
     * @return bool
     * @throws \Phalcon\Logger\Exception
     */
    public function updateTimestamp(string $id, string $data): bool
    {
        if (! $found = $this->getEntry($id)) {
            return true;
        }
        $delay = isset($this->options['ignoring_delay']) ? (int) $this->options['ignoring_delay'] : 0;
        if (! $delay || (time() - (int) $found['modified_on']) > $delay) {
            $query = 'UPDATE session_data SET modified_on = UNIX_TIMESTAMP() WHERE id = ?';
            return $this->catchableRun($query, [$id]);
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
