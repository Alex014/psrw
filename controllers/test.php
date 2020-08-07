<?php
namespace controllers;

class test extends \psrw\BaseController {
    public function run() 
    {
        $this->getStream()->write('runnnnnnnnnn()');
        $this->out();
    }
    
    public function ggg() 
    {
//        $this->getStream()->write('ggg()');
//        $this->out();
        $this->die(['die' => 'DIE DIE DIE !']);
    }
    
    public function ppp() 
    {
        $this->getStream()->write('ppp()');
        $this->out();
    }
}
