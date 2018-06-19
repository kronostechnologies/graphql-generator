<?php


namespace GraphQLGen\Exception;


use Throwable;

class InvalidOutputDirException extends \Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        $message = "Provided output directory is not writable.";

        parent::__construct($message, $code, $previous);
    }
}