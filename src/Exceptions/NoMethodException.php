<?php
namespace psrw\Exceptions;

class NoMethodException extends \Exception 
{
    public function __construct(string $route, string $controller)
    {
        parent::__construct("There is no method for route '$route' and controller '$controller'");
    }
}
