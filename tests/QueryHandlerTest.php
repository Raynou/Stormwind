<?php

use PHPUnit\Framework\TestCase;
use Stormwind\QueryHandler;
use Stormwind\UserNotFoundException;

final class QueryHandlerTest extends TestCase {

    #region getUserInfoWithID tests
    public function testGetUserInfoWithIDIfNotExists() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $this->expectException(UserNotFoundException::class);
        $queryHandler = new QueryHandler();
        $res = $queryHandler->getUserInfoWithID(-1);
    }

    public function testGetUserInfoWithIDIfExists() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $queryHandler = new QueryHandler();
        $res = $queryHandler->getUserInfoWithID(1); // Admin id

        $this->assertArrayHasKey('username', $res);
        $this->assertArrayHasKey('firstname', $res);
        $this->assertArrayHasKey('lastname', $res);
        $this->assertArrayHasKey('email', $res);

    }
    #endregion

}