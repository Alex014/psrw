<?php
namespace psrw;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \Nyholm\Psr7\Factory\Psr17Factory;
use Narrowspark\HttpEmitter\SapiEmitter;
use Narrowspark\HttpEmitter\SapiStreamEmitter;

class BaseController {
    private $request;
    private $stream;
    
    /**
     * 
     * @param ServerRequestInterface $request PSR 7 server request
     */
    public function __construct(\Psr\Http\Message\ServerRequestInterface $request) {
        $this->request = $request;
        
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();
        $this->stream = $psr17Factory->createStream();
    }
    
    /**
     * Return PSR7 Stream
     * @return StreamInterface
     */
    public function getStream(): StreamInterface {
        return $this->stream;
    }
    
    /**
     * Return PSR7 Server Request
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface {
        return $this->request;
    }
    
    /**
     * 
     * @param ResponseInterface $response
     */
    public function emit(ResponseInterface $response) {
        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }
    
    /**
     * 
     * @param ResponseInterface $response
     */
    public function emitRange(ResponseInterface $response) {
        $emitter = new SapiStreamEmitter();
        $emitter->emit($response);
    }
    
    /**
     * send response to client with HTTP code
     * @param int $code
     */
    public function out(int $code = 200) {
        $psr17Factory = new Psr17Factory();
        $response = $psr17Factory->createResponse($code)->withBody($this->stream);
        $this->emit($response);
    }
    
    /**
     * send response to client with HTTP code using range params
     * @param int $code
     */
    public function outRange(int $code = 200) {
        $psr17Factory = new Psr17Factory();
        $response = $psr17Factory->createResponse($code)->withBody($this->stream);
        $this->emitRange($response);
    }
    
    /**
     * dump data and send response to client
     * @param type $data
     */
    public function dump($data) 
    {
        if(is_array($data)) {
            $this->stream->write(print_r($data, true));
        }
        else {
            $this->stream->write($data);
        }
        
        $this->out();
    }
    
    /**
     * dump data and send response to client and finish application
     * @param type $data
     */
    public function die($data) 
    {
        $this->dump($data);
        die();
    }
}
