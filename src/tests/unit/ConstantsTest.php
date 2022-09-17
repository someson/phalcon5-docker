<?php

namespace Tests\Unit;

use Codeception\Test\Unit;

class ConstantsTest extends Unit
{
    public function testShouldCheckTheConstants(): void
    {
        $this->assertTrue(defined('BASE_DIR'));
        $this->assertTrue(defined('APP_DIR')    && APP_DIR === BASE_DIR.DIRECTORY_SEPARATOR.'app');
        $this->assertTrue(defined('LIB_DIR')    && LIB_DIR === BASE_DIR.DIRECTORY_SEPARATOR.'library');
        $this->assertTrue(defined('MODULE_DIR') && MODULE_DIR === APP_DIR.DIRECTORY_SEPARATOR.'Modules');
        $this->assertTrue(defined('SHARED_DIR') && SHARED_DIR === APP_DIR.DIRECTORY_SEPARATOR.'Shared');
        $this->assertTrue(defined('VENDOR_DIR') && VENDOR_DIR === BASE_DIR.DIRECTORY_SEPARATOR.'vendor');
        $this->assertTrue(defined('PUBLIC_DIR') && PUBLIC_DIR === BASE_DIR.DIRECTORY_SEPARATOR.'public');
        $this->assertTrue(defined('TMP_DIR')    && TMP_DIR === BASE_DIR.DIRECTORY_SEPARATOR.'storage');
        $this->assertTrue(defined('CACHE_DIR')  && CACHE_DIR === TMP_DIR.DIRECTORY_SEPARATOR.'cache');
    }
}
