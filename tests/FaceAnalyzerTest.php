<?php

use PHPUnit\Framework\TestCase;
use Stormwind\FaceAnalyzer;
use Dotenv\Dotnenv;

final class FaceAnalyzerTest extends TestCase {

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

        $this->assertTrue(FaceAnalyzer::compareFaces($target, $source));
        $this->assertFalse(FaceAnalyzer::compareFaces($target, $rock));

    }
}
