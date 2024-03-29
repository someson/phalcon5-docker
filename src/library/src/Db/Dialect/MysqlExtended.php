<?php

namespace Library\Db\Dialect;

use Phalcon\Db\Exception;
use Phalcon\Db\Dialect\Mysql;

class MysqlExtended extends Mysql
{
    public function getSqlExpression(array $expression, string $escapeChar = null, $bindCounts = null): string
    {
        if ($expression['type'] === 'functionCall') {
            switch (strtoupper($expression['name'])) {
                case 'DATE_INTERVAL':
                    if (\count($expression['arguments']) !== 2) {
                        throw new Exception('DATE_INTERVAL requires 2 parameters');
                    }

                    return match ($expression['arguments'][1]['value']) {
                        "'MICROSECOND'" => 'INTERVAL ' . $this->getSqlExpression($expression['arguments'][0]) . ' MICROSECOND',
                        "'SECOND'" => 'INTERVAL ' . $this->getSqlExpression($expression['arguments'][0]) . ' SECOND',
                        "'MINUTE'" => 'INTERVAL ' . $this->getSqlExpression($expression['arguments'][0]) . ' MINUTE',
                        "'HOUR'" => 'INTERVAL ' . $this->getSqlExpression($expression['arguments'][0]) . ' HOUR',
                        "'DAY'" => 'INTERVAL ' . $this->getSqlExpression($expression['arguments'][0]) . ' DAY',
                        "'WEEK'" => 'INTERVAL ' . $this->getSqlExpression($expression['arguments'][0]) . ' WEEK',
                        "'MONTH'" => 'INTERVAL ' . $this->getSqlExpression($expression['arguments'][0]) . ' MONTH',
                        "'QUARTER'" => 'INTERVAL ' . $this->getSqlExpression($expression['arguments'][0]) . ' QUARTER',
                        "'YEAR'" => 'INTERVAL ' . $this->getSqlExpression($expression['arguments'][0]) . ' YEAR',
                        default => throw new Exception('DATE_INTERVAL unit is not supported'),
                    };

                case 'FULLTEXT_MATCH':
                    if (\count($expression['arguments']) < 2) {
                        throw new Exception('FULLTEXT_MATCH requires 2 parameters');
                    }

                    $arguments = [];
                    $length = \count($expression['arguments']) - 1;
                    for ($i = 0; $i < $length; $i++) {
                        $arguments[] = $this->getSqlExpression($expression['arguments'][$i]);
                    }

                    return sprintf('MATCH(%s) AGAINST (%s)',
                        implode(', ', $arguments),
                        $this->getSqlExpression($expression['arguments'][$length])
                    );
                    break;

                case 'FULLTEXT_MATCH_BMODE':
                    if (\count($expression['arguments']) < 2) {
                        throw new Exception('FULLTEXT_MATCH requires 2 parameters');
                    }

                    $arguments = [];
                    $length = \count($expression['arguments']) - 1;
                    for ($i = 0; $i < $length; $i++) {
                        $arguments[] = $this->getSqlExpression($expression['arguments'][$i]);
                    }

                    return sprintf('MATCH(%s) AGAINST (%s IN BOOLEAN MODE)',
                        implode(', ', $arguments),
                        $this->getSqlExpression($expression['arguments'][$length])
                    );
                    break;

                case 'REGEXP':
                    if (\count($expression['arguments']) !== 2) {
                        throw new Exception('REGEXP requires 2 parameters');
                    }

                    return $this->getSqlExpression($expression['arguments'][0]) .
                    ' REGEXP (' . $this->getSqlExpression($expression['arguments'][1]) . ')';
                    break;

                case 'FROM_UNIXTIME':
                    if (\count($expression['arguments']) !== 1) {
                        throw new Exception('FROM_UNIXTIME requires 1 integer parameter');
                    }
                    return sprintf('FROM_UNIXTIME(%u)', $this->getSqlExpression($expression['arguments'][0]));
                    break;
            }
        }

        return parent::getSqlExpression($expression, $escapeChar, $bindCounts);
    }
}
