<?php

namespace App\Modules\Cli\Tasks;

use Library\Cli\Output;

class TestTask extends MainTask
{
    /**
     * @example $docker-compose exec app-service php ./scripts/cli.php test test [-s] [-v] [-r]
     * @description(short='(does smth.)')
     * @return void
     */
    public function testAction(): void
    {
        Output::text(__FUNCTION__);
    }
}
