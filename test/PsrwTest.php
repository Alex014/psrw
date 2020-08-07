<?php
require '../vendor/autoload.php';
require '../src/Router.php';
require '../src/BaseController.php';
require '../src/Exceptions/NoMethodException.php';
require '../src/Exceptions/NotBaseController.php';
require 'test.php';
require 'test_fake.php';

use PHPUnit\Framework\TestCase;
use \Confy\Confy;

final class PsrwTest extends TestCase
{
    public function testRouting(): void
    {
        Confy::load('config.php');
        $_GET = ['test-run' => ''];
        $r = new \psrw\Router(Confy::get('routes'));
        $r->run();
        $this->assertTrue(true);
    }

    public function testNotBaseController(): void
    {
        $this->expectException(psrw\Exceptions\NotBaseController::class);
        
        Confy::load('config.php');
        $_GET = ['test-fake' => ''];
        $r = new \psrw\Router(Confy::get('routes'), '');
        $r->run();
    }
    
    public function testNoMethod(): void
    {
        $this->expectException(\psrw\Exceptions\NoMethodException::class);
        
        Confy::load('config.php');
        $_GET = ['test-no' => ''];
        $r = new \psrw\Router(Confy::get('routes'), '');
        $r->run();
        $this->assertTrue(true);
    }
}