<?php

namespace Library\Cli;

class PhpError
{
    protected Reporter $_reporter;

    public function __construct(Reporter $persister)
    {
        $this->_reporter = $persister;
    }

    /**
     * @param int|null $errNo
     * @param string|null $errStr
     * @param string|null $errFile
     * @param int|null $errLine
     * @return bool
     */
    public function errorHandler(
        ?int $errNo = null,
        ?string $errStr = null,
        ?string $errFile = null,
        ?int $errLine = null
    ): bool {
        if ((int) $errNo !== E_STRICT) {
            $serverName = php_uname('n');
            $this->_reporter->report([
                'title' => $errStr,
                'file' => $errFile,
                'line' => $errLine,
                'error_type' => $errNo,
                'server_name' => $serverName,
                'execution_script' => $_SERVER['PHP_SELF'],
                'pid' => getmypid(),
                'ip_address' => gethostbyname($serverName)
            ]);
            return true;
        }
        return false;
    }

    public function runtimeShutdown(): void
    {
        if ($e = error_get_last()) {
            $this->errorHandler($e['type'], $e['message'], $e['file'], $e['line']);
        }
    }
}

