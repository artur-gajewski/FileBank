<?php

namespace FileBank;

use FileBank\Entity\File;

class Manager
{
    /**
     * @var Array 
     */
    protected $params;

    /**            
     * @var Doctrine\ORM\EntityManager
     */                
    protected $em;
    
    /**
     * @var array
     */
    protected $cache;
    
    /**
     * Set the Module specific configuration parameters
     * 
     * @param Array $params
     * @param Doctrine\ORM\EntityManager $em 
     */
    public function __construct($params, $em) {
        $this->params = $params;
        $this->em = $em;
        $this->cache = array();
    }

    /**
     * Get the FileBank's root folder
     * 
     * @return string 
     */
    public function getRoot() 
    {
        return $this->params['filebank_folder'];
    }
    
    /**
     * Save file to FileBank database
     * 
     * @param string $sourceFilePath
     * @return string $relativeFilePath
     * @throws \Exception 
     */
    public function save($sourceFilePath)
    {
        $fileName = basename($sourceFilePath);
        $mimetype = mime_content_type($sourceFilePath);
        
        $hash = md5(microtime(true) . $fileName);
        $savePath = substr($hash,0,1).'/'.substr($hash,1,1).'/';
        
        $data = array('name'     => $fileName,
                      'mimetype' => $mimetype,
                      'size'     => filesize($sourceFilePath),
                      'isactive' => $this->params['default_is_active'],
                      'savepath' => $savePath . $hash,
                     );
        
        $file = new File();
        $file->populate($data);
        
        $this->em->persist($file);
        $this->em->flush();
        
        $absolutePath = $this->params['filebank_folder'] . $savePath . $hash;
        
        try {
            $this->createPath($absolutePath, $this->params['chmod'], true);
            copy($sourceFilePath, $absolutePath);
        } catch (Exception $e) {
            throw new \Exception('File cannot be saved.');
        }

        return $file;
    }
    
    /**
     * Create path recursively
     * 
     * @param string $path
     * @param string $mode
     * @param boolean $isFileIncluded 
     * @return boolean
     */
    protected function createPath($path, $mode, $isFileIncluded)
    {
        if (!is_dir(dirname($path))) {
            if ($isFileIncluded) {
                mkdir(dirname($path), $mode, true);
            } else {
                mkdir($path, $mode, true);
            }
        }
    }
    
    /**
     * Get the file entity based on ID
     * 
     * @param integer $fileId
     * @return FileBank\Entity\File 
     * @throws \Exception 
     */
    
    public function getFileById($fileId)
    {
        // Get the entity from cache if available
        if (isset($this->cache[$fileId])) {
            $entity = $this->cache[$fileId];
        } else {
            $entity = $this->em->find('FileBank\Entity\File', $fileId);
        }
        
        if (!$entity) {
            throw new \Exception('File does not exist.', 404);
        }
        
        // Cache the file entity so we don't have to access db on each call
        // Enables to get multiple entity's properties at different times
        $this->cache[$fileId] = $entity;
        return $entity;
    }
    
}
