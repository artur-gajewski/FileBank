<?php
/**
 * @author Levis Florian <https://github.com/Gounlaf>
 */
namespace FileBank\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Validator as ZendValidator;

use FileBank\Exception;
use FileBank\Validator as FileBankValidator;

class Octal extends AbstractFilter
{
    const NOT_DIGITS = 1;
    const NOT_OCTAL = 2;

    /**
     * Digits validator used for validation
     *
     * @var \Zend\Validator\Digits
     */
    protected static $validatorDigits = null;

    /**
     * Octal validator used for validation
     *
     * @var \FileBank\Validator\Octal
     */
    protected static $validatorOctal = null;

    /**
     * {@inheritDoc}
     */
    public function filter($value)
    {
        // Value is already an integer ; nothing to do
        if (is_int($value)) {
            return $value;
        }

        if (null === static::$validatorDigits) {
            static::$validatorDigits = new ZendValidator\Digits();
        }

        if (!static::$validatorDigits->isValid($value)) {
            throw new Exception\InvalidArgumentException(
                'Cannot filter Octal value',
                self::NOT_DIGITS,
                new ZendValidator\Exception\InvalidArgumentException(implode(' ; ', static::$validatorDigits->getMessages()))
            );
        }

        if (null === static::$validatorOctal) {
            static::$validatorOctal = new FileBankValidator\Octal();
        }

        if (!static::$validatorOctal->isValid($value)) {
            throw new Exception\InvalidArgumentException(
                'Cannot filter Octal value',
                self::NOT_OCTAL,
                new Exception\InvalidArgumentException(implode(' ; ', static::$validatorOctal->getMessages()))
            );
        }

        return intval($value, 8);
    }
}
