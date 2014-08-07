<?php
/**
 * @author Levis Florian <https://github.com/Gounlaf>
 */
namespace FileBank\Validator;

use Zend\Validator\AbstractValidator;
use Zend\Validator\Digits;

use FileBank\Validator\Octal;

class Chmod extends AbstractValidator
{
    const NOT_CHMOD     = 'notChmod';
    const NOT_DIGITS    = 'notDigits';
    const STRING_EMPTY  = 'chmodStringEmpty';
    const INVALID       = 'chmodInvalid';

    /**
     * Digits validator used for validation
     *
     * @var \Zend\Validator\Digits
     */
    protected static $validatorDigits = null;

    /**
     * Octal validator used for validation
     *
     * @var \Application\Validator\Octal
     */
    protected static $validatorOctal = null;

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_CHMOD     => "The input is not a valid mode",
        self::NOT_DIGITS    => "The input must contain only digits",
        self::STRING_EMPTY  => "The input is an empty string",
        self::INVALID       => "Invalid type given. String or integer expected",
    );

    public function isValid($value)
    {
        $this->setValue($value);

        if (!is_string($value) && !is_int($value)) {
            $this->error(self::INVALID);
            return false;
        }

        // TODO: Validate string like -rw-r-xr-x ?
        if (is_string($value)) {
            if ('' === $value) {
                $this->error(self::STRING_EMPTY);
                return false;
            }

            if (null === static::$validatorDigits) {
                static::$validatorDigits = new Digits();
            }

            if (!static::$validatorDigits->isValid($value)) {
                $this->error(self::NOT_CHMOD);
                return false;
            }

            $length = strlen($value);
            if ($length > 4 || $length < 3) {
                $this->error(self::NOT_CHMOD);
                return false;
            }

            // Convert to octal
            $value = octdec($value);
        }

        if (null === static::$validatorOctal) {
            static::$validatorOctal = new Octal();
        }

        if (static::$validatorOctal->isValid($value)) {
            // Convert to decimal
            $value = intval($value, 10);
        }

        // 0777 - 0000
        if ($value > 511 || $value < 0) {
            $this->error(self::NOT_CHMOD);
            return false;
        }

        return true;
    }
}
