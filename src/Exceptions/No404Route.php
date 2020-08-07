<?php
namespace psrw\Exceptions;

class No404Route extends \Exception 
{
    public function __construct()
    {
        parent::__construct("There is no 404 route in routes");
    }
}
