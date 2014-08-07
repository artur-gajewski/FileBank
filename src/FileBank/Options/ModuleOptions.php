<?php
namespace FileBank\Options;

use Zend\Stdlib\AbstractOptions;
use Zend\Filter;

class ModuleOptions extends AbstractOptions
{
    /**
     * Filebank data storage
     *
     * @var string Path to directory
     */
    protected $fileBankFolder = '';

    /**
     * Mode for FileBank data storage (and children)
     * (must be in octal !)
     *
     * @var int
     */
    protected $chmod = 0755;

    /**
     * Default active status for FileEntity
     *
     * @var boolean
     */
    protected $defaultIsActive = true;

    public static function getServiceKey()
    {
        return __CLASS__;
    }

    /**
     * Get FileBank data storage path
     * @return string
     */
    public function getFileBankFolder()
    {
        return $this->fileBankFolder;
    }

    /**
     * Set FileBank data storage path
     *
     * @param string $fileBankFolder
     * @return \FileBank\Options\ModuleOptions
     */
    public function setFileBankFolder($fileBankFolder)
    {
        $this->fileBankFolder = $fileBankFolder;
        return $this;
    }

    /**
     *
     * @return int
     */
    public function getChmod()
    {
        return $this->chmod;
    }

    /**
     *
     * @param $chmod
     * @return \FileBank\Options\ModuleOptions
     */
    public function setChmod($chmod)
    {
        $this->chmod = $chmod;
        return $this;
    }

    /**
     *
     * @return the boolean
     */
    public function getDefaultIsActive()
    {
        return $this->defaultIsActive;
    }

    /**
     *
     * @param $defaultIsActive
     * @return \FileBank\Options\ModuleOptions
     */
    public function setDefaultIsActive($defaultIsActive)
    {
        // Allow multiple value for default_is_active option
        $filter = new Filter\Boolean(Filter\Boolean::TYPE_ZERO_STRING);

        $this->defaultIsActive = $filter->filter($defaultIsActive);
        return $this;
    }
}
