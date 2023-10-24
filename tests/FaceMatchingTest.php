<?php

use PHPUnit\Framework\TestCase;
use Stormwind\FaceMatching;
use Dotenv\Dotnenv;

final class FaceMatchingTest extends TestCase {

    public function testLoadEnv() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $this->assertSame($_ENV["TEST"], "TEST");
    }

    public function testCompareFaces() {

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $target = __DIR__ . "/public/target.jpg";
        $source = __DIR__ . "/public/source.jpg";
        $rock = __DIR__ . "/public/rock.jpg";

        $this->assertTrue(FaceMatching::compareFaces($target, $source));
        $this->assertFalse(FaceMatching::compareFaces($target, $rock));

    }
}