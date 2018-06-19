<?php


namespace GraphQLGen\Exception;


use Throwable;

class InvalidInputFileException extends \Exception
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        $message = "Provided input file is not readable.";

        parent::__construct($message, $code, $previous);
    }
}