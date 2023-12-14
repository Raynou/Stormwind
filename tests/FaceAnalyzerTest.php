<?php

use PHPUnit\Framework\TestCase;
use Stormwind\FaceAnalyzer;
use Stormwind\ImageHandler;
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

    public function testDetectFeelings() {

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $rock = __DIR__ . "/public/rock.jpg";
        $sadHappy = __DIR__ . "/public/sadAndHappy.jpg";

        $sadHappyUri = ImageHandler::imageToBase64($sadHappy);
        $sadHappyRes = FaceAnalyzer::detectFeelings($sadHappyUri)[0]["Type"];

        $rockUri = ImageHandler::imageToBase64($rock);
        $rockRes = FaceAnalyzer::detectFeelings($rockUri)[0]["Type"];

        $this->assertEquals($sadHappyRes,"SAD");
        $this->assertEquals($rockRes,"HAPPY");

    }
}
