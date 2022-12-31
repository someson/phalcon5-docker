<?php

namespace Tests\Unit;

use App\Shared\Notification;
use Codeception\Test\Unit;

class NotificationTest extends Unit
{
    public function testShouldBeFailed()
    {
        $result = Notification::failure();
        $this->assertTrue($result->isFailed());
    }

    public function testShouldBeSucceeded()
    {
        $result = Notification::success();
        $this->assertFalse($result->isFailed());
    }

    public function testShouldBeAJson()
    {
        $result = Notification::success()->with(['otherData' => 'value']);
        $this->assertJson(json_encode($result, JSON_THROW_ON_ERROR));
    }

    public function testShouldContainSpecificText()
    {
        $message = 'Test Success Message';
        $result = Notification::success($message);
        $this->assertEquals($result->getMessage(), $message);
    }

    public function testMustContainTheSpecificKey()
    {
        $keyName = 'key';
        $result = Notification::failure()->with([$keyName => 'value']);
        $this->assertArrayHasKey($keyName, $result->jsonSerialize());
    }
}
