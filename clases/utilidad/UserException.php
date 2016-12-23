<?php
class UserException extends Exception{
    
    const SUCCESS = '1';
    const INFO = '2';
    const WARNING = '3';
    const ERROR = '4';
   
    /**
     * <p>lanza excepción para el usuario</p>
     * @param String $message
     * @param type $code <p> puede ser <ul><li>UserException::SUCCESS</li><li>UserException::INFO</li><li>UserException::WARNING</li><li>UserException::ERROR</li></ul>
     */
    public function __construct($message = "",$code) {
        parent::__construct($message, $code);
        
    }
}
