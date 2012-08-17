<?php

namespace FileBank\Entity;

use Doctrine\ORM\Mapping as ORM;
use FileBank\Entity\File;

/**
 * Keyword entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="FILEBANK_KEYWORD")
 * @property int $id
 * @property int $fileid
 * @property string $value
 */
class Keyword
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="FileBank\Entity\File", inversedBy="keywords")
     * @ORM\JoinColumn(name="fileid", referencedColumnName="id")
     * @var ArrayCollection
     */
    protected $file;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $value;

    /**
     * Getter for the keyword id
     * 
     * @return int 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Setter for the keyword id
     * 
     * @param int $value 
     */
    public function setId($value)
    {
        $this->id = $value;
    }
    
    /**
     * Getter for the file id
     * 
     * @return int 
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Setter for the file id
     * 
     * @param int $value 
     */
    public function setFile($value)
    {
        $this->file = $value;
    }
    
    /**
     * Getter for the keyword value
     * 
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Setter for the keyword value
     * 
     * @param string $value 
     */
    public function setValue($value)
    {
        $this->value = $value;
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
        $this->setFileId($data['fileid']);
        $this->setValue($data['value']);
    }
       
}

