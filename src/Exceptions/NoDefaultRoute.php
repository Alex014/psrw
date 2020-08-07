<?php
namespace psrw\Exceptions;

class NoDefaultRoute extends \Exception 
{
    public function __construct()
    {
        parent::__construct("There is no default route in routes");
    }
}
