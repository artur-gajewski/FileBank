<?php

namespace FileBank\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Http\Request;

use FileBank\Entity\File;

class FileBank extends AbstractHelper
{
    /**
     * @var FileBank Service
     */
    protected $service;
    
    /**
     * @var array $params
     */
    protected $params;
    
    /**
     * Called upon invoke
     * 
     * @param integer $id
     * @return FileBank\Entity\File
     */
    public function __invoke($id)
    {
        $file = $this->service->getFileById($id);
        
        $urlHelper = $this->getView()->plugin('url');
        
        $file->setPath($this->params['filebank_folder'] . $file->getId() . '/' . $file->getName());
        $file->setDownloadUrl($urlHelper('FileBank') . '/' . $file->getId());

        return $file;
    }

    /**
     * Get FileBank service.
     *
     * @return $this->service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set FileBank service.
     *
     * @param $service
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }
    
    /**
     * Get FileBank params.
     *
     * @return $this->params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set FileBank params.
     *
     * @param array $params
     */
    public function setParams(Array $params)
    {
        $this->params = $params;
        return $this;
    }
    
}