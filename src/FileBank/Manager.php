<?php
namespace FileBank;

use FileBank\Entity\File;
use FileBank\Entity\Keyword;
use FileBank\Exception;
use FileBank\Exception\RuntimeException;

class Manager
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var array
     */
    protected $cache;

    /**
     * @var \FileBank\Entity\File
     */
    protected $file;

    /**
     * @var array
     */
    protected $mime_types = array(

        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/msword',
        'xlsx' => 'application/vnd.ms-excel',
        'pptx' => 'application/vnd.ms-powerpoint',


        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    /**
     * Set the Module specific configuration parameters
     *
     * @param array $params
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct($params, $em) {
        $this->params = $params;
        $this->em = $em;
        $this->cache = array();
    }

    /**
     * Detetcs the mimetype of a file
     *
     * @param string $sourceFilePath
     * @param int $mode mode 0 = full check, mode 1 = extension check only
     * @return string
     */
    private function getMimeType($sourceFilePath, $mode = 0)
    {
        $fileExtension = explode('.',$sourceFilePath);
        $ext = strtolower(array_pop($fileExtension));
        if (function_exists('mime_content_type') && $mode == 0) {
            $mimetype = mime_content_type($sourceFilePath);
            return $mimetype;
        } elseif (function_exists('finfo_open') && $mode == 0) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $sourceFilePath);
            finfo_close($finfo);
            return $mimetype;
        } elseif (array_key_exists($ext, $this->mime_types)) {
            return $this->mime_types[$ext];
        } else {
            return 'application/octet-stream';
        }
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
     * Get the file entity based on ID
     *
     * @param integer $fileId
     * @return \FileBank\Entity\File
     * @throws \FileBank\Exception\FileNotFoundException
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
            throw new Exception\FileNotFoundException(
                'File does not exist.', 404
            );
        }

        // Cache the file entity so we don't have to access db on each call
        // Enables to get multiple entity's properties at different times
        $this->cache[$fileId] = $entity;
        return $entity;
    }

    /**
     * Get array of file entities based on given keyword
     *
     * @param array $keywords
     * @return array
     */
    public function getFilesByKeywords($keywords)
    {
        // Create unique ID of the array for cache
        $id = md5(serialize($keywords));

        // Change all given keywords to lowercase
        $keywords = array_map('strtolower', $keywords );

        // Get the entity from cache if available
        if (isset($this->cache[$id])) {
            return $this->cache[$id];
        }

        $list = "'" . implode("','", $keywords) . "'";

        $q = $this->em->createQuery(
            "select f from FileBank\Entity\File f, FileBank\Entity\Keyword k
             where k.file = f
             and k.value in (" . $list . ")"
            );

        // Cache the file entity so we don't have to access db on each call
        // Enables to get multiple entity's properties at different times
        $this->cache[$id] = $q->getResult();
        return $this->cache[$id];
    }

    /**
     * Save file to FileBank database
     *
     * @param string $sourceFilePath
     * @param array $keywords
     * @return \FileBank\Entity\File
     * @throws \FileBank\Exception\RuntimeException
     */
    public function save($sourceFilePath, array $keywords = array())
    {
        $fileName = basename($sourceFilePath);
        //$mimetype = mime_content_type($sourceFilePath);
        $mimetype = $this->getMimeType($sourceFilePath);
        $hash     = md5(microtime(true) . $fileName);
        $savePath = substr($hash,0,1).'/'.substr($hash,1,1).'/';

        $this->file = new File();
        $this->file->setName($fileName);
        $this->file->setMimetype($mimetype);
        $this->file->setSize(filesize($sourceFilePath));
        $this->file->setIsActive($this->params['default_is_active']);
        $this->file->setSavepath($savePath . $hash);
        $this->addKeywordsToFile($keywords);

        $this->em->persist($this->file);
        $this->em->flush();

        $absolutePath = $this->params['filebank_folder'] . $savePath . $hash;

        try {
            $this->createPath($absolutePath, $this->params['chmod'], true);
            copy($sourceFilePath, $absolutePath);
        } catch (\Exception $e) {
            throw new Exception\RuntimeException(
                'File cannot be saved.', 0, $e
            );
        }

        return $this->file;
    }

    /**
     * Attach keywords to file entity
     *
     * @param array $keywords
     * @return \FileBank\Entity\File
     */
    protected function addKeywordsToFile(array $keywords)
    {
        if (!empty($keywords)) {
            $keywordEntities = array();

            foreach ($keywords as $word) {
                $keyword = new Keyword();
                $keyword->setValue(strtolower($word));
                $keyword->setFile($this->file);
                $this->em->persist($keyword);

                $keywordEntities[] = $keyword;
            }

            $this->file->setKeywords($keywordEntities);
        }

        return $this->file;
    }

    /**
     * Create path recursively
     *
     * @param string $path
     * @param string $mode
     * @param boolean $isFileIncluded
     *
     * @throws \FileBank\Exception\RuntimeException
     */
    protected function createPath($path, $mode, $isFileIncluded)
    {
        $success = true;

        if (!is_dir(dirname($path))) {
            if ($isFileIncluded) {
                $success = mkdir(dirname($path), $mode, true);
            } else {
                $success = mkdir($path, $mode, true);
            }
        }

        if (!$success) {
            throw new Exception\RuntimeException('Can\'t create filebank storage folders');
        }
    }
}
