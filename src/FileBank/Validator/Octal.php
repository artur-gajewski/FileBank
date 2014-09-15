<?php
/**
 * @author Levis Florian <https://github.com/Gounlaf>
 */
namespace FileBank\Validator;

use Zend\Validator\AbstractValidator;

class Octal extends AbstractValidator
{
    const NOT_OCTAL     = 'notOctal';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates = array(
        self::NOT_OCTAL     => "The input is not octal",
    );

    /**
     * Returns true if the value is written in octal
     *
     * @param  mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        // /!\ IMPORTANT: Don't use strict equality here
        if (decoct(octdec($value)) != $value) {
            $this->error(self::NOT_OCTAL);
            return false;
        }

        return true;
    }
}
