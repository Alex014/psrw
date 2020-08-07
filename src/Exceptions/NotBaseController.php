<?php
namespace psrw\Exceptions;

class NotBaseController extends \Exception 
{
    public function __construct(string $controller)
    {
        parent::__construct("Class '$controller' is not subclass of '\\psrw\\BaseController'");
    }
}
