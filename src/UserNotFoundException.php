<?php
namespace Stormwind;
use Exception;

/**
 * This exception is thrown when an user is not found in the
 * Moodle Database.
 */
final class UserNotFoundException extends Exception
{
     /**
     * NotSupportedException constructor.
     *
     * @param string $message The exception message to throw.
     * @param int $code The exception code.
     * @param Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct($message = '',$code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}