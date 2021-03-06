<?php

require __DIR__ . '/../../vendor/autoload.php';

class DatabaseTest extends PHPUnit\Framework\TestCase
{
    public function testConnectionIsCorrectlyInitialised()
    {
        $mysqli = $this->createMock(mysqli::class);
        $subject = new \GamingPassion\Database($mysqli);

        $this->assertEquals($subject->connection, $mysqli);
    }
}
