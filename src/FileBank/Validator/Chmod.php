<?php
/**
 * @author Levis Florian <https://github.com/Gounlaf>
 */
namespace FileBank\Validator;

use Zend\Validator\AbstractValidator;

use FileBank\Filter;
use FileBank\Exception;

class Chmod extends AbstractValidator
{
    const NOT_CHMOD     = 'notChmod';
    const NOT_DIGITS    = 'notDigits';
    const NOT_OCTAL     = 'notOctal';
    const STRING_EMPTY  = 'chmodStringEmpty';
    const INVALID       = 'chmodInvalid';

    /**
     * Octal validator used for validation
     *
     * @var \Application\Filter\Octal
     */
    protected static $filterOctal = null;

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_CHMOD     => 'The input is not a valid file mode',
        self::NOT_DIGITS    => 'The input must contain only digits',
        self::NOT_OCTAL     => 'The input is not octal or cannot be converted in octal',
        self::STRING_EMPTY  => 'The input is an empty string',
        self::INVALID       => 'Invalid type given. String or integer expected'
    );

    /**
     * {@inheritDoc}
     */
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
        }

        if (null === static::$filterOctal) {
            static::$filterOctal = new Filter\Octal();
        }

        try {
            $value = static::$filterOctal->filter($value);
        } catch (Exception\InvalidArgumentException $e) {
            switch ($e->getCode()) {
                case Filter\Octal::NOT_DIGITS:
                    $this->error(self::NOT_DIGITS);
                    break;

                case Filter\Octal::NOT_OCTAL:
                    $this->error(self::NOT_OCTAL);
                    break;
            }

            return false;
        }

        if ($value > 0777 || $value < 0) {
            $this->error(self::NOT_CHMOD);
            return false;
        }

        return true;
    }
}
