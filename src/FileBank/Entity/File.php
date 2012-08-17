<?php

namespace FileBank\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FileBank\Entity\Keyword;

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
 * @property string $savepath
 * @property ArrayCollection $keywords
 */
class File 
{
    /**
     * Default constructor, initializes collections
     */
    public function __construct() 
    {
        $this->keywords = new ArrayCollection();
    }

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
     * @ORM\Column(type="string")
     */
    protected $savepath;

    /**
     * @ORM\OneToMany(targetEntity="FileBank\Entity\Keyword", mappedBy="file")
     * @ORM\OrderBy({"id" = "ASC"})
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $keywords;

    /**
     * @var string $downloadUrl 
     */
    protected $url;

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
        return $this->isactive;
    }

    /**
     * Setter for the file's active status
     * 
     * @param int $value 
     */
    public function setIsActive($value) 
    {
        $this->isactive = $value;
    }

    /**
     * Getter for the file's save path
     * 
     * @return string 
     */
    public function getSavePath() 
    {
        return $this->savepath;
    }

    /**
     * Setter for the file's save path
     * 
     * @param string $value 
     */
    public function setSavePath($value) 
    {
        $this->savepath = $value;
    }

    /**
     * Getter for the file's keywords
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getKeywords() 
    {
        return $this->keywords;
    }

    /**
     * Setter for the file's keywords
     */
    public function setKeywords(Array $keywords) 
    {
        $this->keywords->clear();
        foreach ($keywords as $keyword) {
            if ($keyword instanceof FileBank\Entity\Keyword) {
                $this->keywords->add($keyword);
            }
        }
    }

    /**
     * Getter for the file's download URL
     * 
     * @return string 
     */
    public function getUrl() 
    {
        return $this->url;
    }

    /**
     * Setter for the file's download URL
     * 
     * @param string $value
     */
    public function setUrl($value) 
    {
        $this->url = $value;
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
        $this->setSavePath($data['savepath']);
    }
}

