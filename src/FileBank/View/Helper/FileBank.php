<?php

namespace FileBank\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Http\Request;

use FileBank\Entity\File as File;

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
     * 
     * Link Options:
     *   class  = CSS class
     *   prefix = ID prefix, will append -<fileid>
     *   target = target for the link
     */
    public function __invoke($id, $linkOptions = null)
    {
        $file = $this->service->getFileById($id);
        $file = $this->generateDynamicParameters($file);
        return $file;
    }

    /**
     * Add dynamic data into the entity
     * 
     * @param FileBank\Entity\File $file
     * @param Array $linkOptions
     * @return FileBank\Entity\File
     */
    private function generateDynamicParameters(File $file) 
    {
        $urlHelper = $this->getView()->plugin('url');
        
        $file->setDownloadUrl(
                $urlHelper('FileBank') . '/' . $file->getId()
                );
        
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