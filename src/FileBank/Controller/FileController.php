<?php
namespace FileBank\Controller;

use SplFileInfo;
use DateTime;

use Zend\Http;
use Zend\Mvc\Controller\AbstractActionController;

use FileBank\Exception\RuntimeException;

class FileController extends AbstractActionController
{
    /**
     * Get the file from FileBank and offer it for download
     */
    public function downloadAction()
    {
        /* @var $filelib \FileBank\Manager */
        $filelib = $this->getServiceLocator()->get('FileBank');
        $id = (int) $this->getEvent()->getRouteMatch()->getParam('id');

        // Will throw 404 error if file not found
        $file = $filelib->getFileById($id);

        $fileInfo = new SplFileInfo($filelib->getRoot() . $file->getSavePath());
        if (!$fileInfo->isFile() || !$fileInfo->isReadable()) {
            throw new RuntimeException('Cannot read file');
        }

        $response = new Http\Response\Stream();
        // SplFile are not resources :(
        $response->setStream(fopen($fileInfo->getRealPath(), 'r'))
            ->setStatusCode(Http\Response::STATUS_CODE_200);

        $expires = new DateTime();
        $expires->setTimestamp(0);

        $response->getHeaders()
            ->addHeaderLine('Content-Type', $file->getMimetype())
            ->addHeaderLine('Content-Disposition', 'attachment; filename=' . $file->getName())
            ->addHeaderLine('Content-Transfer-Encoding', 'binary')
            ->addHeaderLine('Content-Length', $fileInfo->getSize())
            // Cache
            ->addHeaderLine('Cache-Control', 'must-revalidate')
            ->addHeaderLine('Expires', $expires->format(DateTime::RFC1123))
            ->addHeaderLine('Pragma', 'public');

        return $response;
    }
}
