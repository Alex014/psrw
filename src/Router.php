<?php
namespace psrw;

use psrw\Exceptions\NoMethodException;
use psrw\Exceptions\NotBaseController;
use psrw\Exceptions\No404Route;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

class Router {
    
    private $serverRequest;
    private $routes;
    private $route;
    
    private $serverParams;
    private $queryParams;
    
    private $method;
    
    /**
     * 
     * @param array $routes
     * Example: [
        'test-run' => '\\controllers\\test.',
        'rrr' => [
            'GET' => '\\controllers\\test.ggg',
            'POST' => '\\controllers\\test.ppp'
        ]
    ]
     * 
     * Where 'test-run' is route and '\\controllers\\test' 
     * is controller class with namespace 
     * and 'run' is controller method
     */
    public function __construct(array $routes) 
    {
        $psr17Factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        $this->serverRequest = $creator->fromGlobals();

        $this->routes = $routes;
        
        // var_dump($serverRequest->getBody());
        $this->serverParams = $this->serverRequest->getServerParams();
        $this->queryParams = $this->serverRequest->getQueryParams();
        $this->method = $this->serverRequest->getMethod();
    }
    
    /**
     * Run the controller depending on route (?route in GET params)
     * @return boolean
     */
    public function run() 
    {
        foreach ($this->queryParams as $key => $value) {
            $this->route = $key;
            break;
        }
        
        foreach ($this->routes as $preg => $controller) {
            if (preg_match("/$preg/i", $this->route)) {
                if(is_array($controller)) {
                    // controller is array: [POST|GET|... => controller.method]
                    foreach ($controller as $httpMethod => $controllerRun) {
                        if(strtoupper($httpMethod) == $this->method) {
                            $this->runControllerString($preg, $controllerRun);
                            break;
                        }
                    }
                } else {
                    // controller is string controller.method
                    $this->runControllerString($preg, $controller);
                    return true;
                }
            }
        }
        
        if(!empty($this->routes['404'])) {
            $this->runControllerString('404', $this->routes['404']);
        } else {
            throw new No404Route();
        }
        
        return false;
    }

    
    private function runControllerString(string $preg, string $controllerPath) 
    {
        $controller = explode('.', $controllerPath);
        $method = $controller[1];
        $controller = $controller[0];

        if (empty($method)) {
            throw new NoMethodException($preg, $controller);
        }

        $this->runController($controller, $method);
    }
    
    private function runController(string $controller, string $method) 
    {
        $builder = new \DI\ContainerBuilder();
        $builder->useAutowiring(false);
        $builder->useAnnotations(false);

        $container = $builder->build();
        
        $cntrl = new $controller($this->serverRequest);

        if (!is_a($cntrl, '\\psrw\\BaseController')) {
            throw new NotBaseController($controller);
        }

        $container->set('controller', $cntrl);
        $cntrl->$method();
    }
}
