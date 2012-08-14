<?php

namespace FileBank\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="FILEBANK")
 * @property int $id
 * @property string $name
 * @property int $size
 * @property string $mimetype
 * @property string $isactive
 */
class File
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $size;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $mimetype;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $isactive;
    
    /**
     * @var string $path 
     */
    protected $path;
    
    /**
     * @var string $downloadUrl 
     */
    protected $downloadUrl;
    
    /**
     * Getter for the file id
     * 
     * @return int 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Setter for the file id
     * 
     * @param int $value 
     */
    public function setId($value)
    {
        $this->id = $value;
    }
    
    /**
     * Getter for the file name
     * 
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Setter for the file name
     * 
     * @param string $value 
     */
    public function setName($value)
    {
        $this->name = $value;
    }
    
    /**
     * Getter for the file size
     * 
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
    
    /**
     * Setter for the file size
     * 
     * @param int $value 
     */
    public function setSize($value)
    {
        $this->size = $value;
    }
    
    /**
     * Getter for the file mimetype
     * 
     * @return string 
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }
    
    /**
     * Setter for the file mimetype
     * 
     * @param int $value 
     */
    public function setMimetype($value)
    {
        $this->mimetype = $value;
    }
    
    /**
     * Getter for the file's active status
     * 
     * @return int 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
    
    /**
     * Setter for the file's active status
     * 
     * @param int $value 
     */
    public function setIsActive($value)
    {
        $this->isActive = $value;
    }
    
    /**
     * Getter for the file's path
     * 
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
    
    /**
     * Setter for the file's path
     * 
     * @param string $value
     */
    public function setPath($value)
    {
        $this->path = $value;
    }
    
    /**
     * Getter for the file's download URL
     * 
     * @return string 
     */
    public function getDownloadUrl()
    {
        return $this->downloadUrl;
    }
    
    /**
     * Setter for the file's download URL
     * 
     * @param string $value
     */
    public function setDownloadUrl($value)
    {
        $this->downloadUrl = $value;
    }
    
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Populate from an array.
     *
     * @param array $data
     */
    public function populate($data = array())
    {
        $this->setName($data['name']);
        $this->setSize($data['size']);
        $this->setMimetype($data['mimetype']);
        $this->setIsActive($data['isactive']);
    }
       
}

