## PSRW
This is WEB MVC framework with PSR-7 and PSR-17 support
It uses *Nyholm psr7* library https://github.com/Nyholm/psr7 and *narrowspark http-emitter* https://github.com/narrowspark/http-emitter
### Instalation and usage
`composer require alex014/psrw`
#### With external configuration
```
<?php
require 'vendor/autoload.php';

use \Confy\Confy;

Confy::load('config.php');
$r = new \psrw\Router(Confy::get('routes'));
$r->run();
```
#### Without
```
require 'vendor/autoload.php';

$r = new \psrw\Router([
        'test-run' => '\\controllers\\test.run',
        'rrr' => [
            'GET' => '\\controllers\\test.ggg',
            'POST' => '\\controllers\\test.ppp'
        ]
    ]);
$r->run();
```
### Controller
```
namespace controllers;

class test extends \psrw\BaseController {
    public function run() 
    {
        $this->getStream()->write('runnnnnnnnnn()'); //Write output
        $this->out(); //Emit stream
    }
}
```
### Controller methods
`getStream(): StreamInterface` - Return PSR7 Stream to write output
`getRequest(): ServerRequestInterface` - Return PSR7 Server Request
`emit(ResponseInterface $response)` - Emit your PSR7 Response using system defined emiter
`emitRange(ResponseInterface $response)` - Emit your PSR7 Response using range params and system defined emiter
`out(int $code = 200)` - send response to client with HTTP code
`outRange(int $code = 200)` - send response to client with HTTP code using range params
`dump($data)` - dump data and send response to client
`die($data)` - dump data and send response to client and finish application

### Run tests
* Install PHPUnit `wget -O phpunit https://phar.phpunit.de/phpunit-9.phar` and `chmod +x phpunit`
* Run tests `./phpunit PsrwTest.php`
### License
MIT license