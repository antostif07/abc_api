<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{    
    private $uploadPath;

    private $slugger;

    private $urlHelper;

    private $relativeUploaderDir;

    public function __construct($publicPath, $uploadPath, SluggerInterface $slugger, UrlHelper $urlHelper)
    {
        $this->uploadPath = $uploadPath;
        $this->slugger = $slugger;
        $this->urlHelper = $urlHelper;
        $this->relativeUploaderDir = str_replace($publicPath, '', $this->uploadPath).'/';
    }

    public function upload(UploadedFile $file)
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFileName);
        $filename = $safeFilename.'-'. uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getUploadPath(), $filename);
        } catch (FileException $e) {
            return;
        }

        return $filename;
    }

    public function getUploadPath()
    {
        return $this->uploadPath;
    }

    public function getUrl(?string $fileName, bool $absolute = true)
    {
        if(empty($fileName)) return null;

        if($absolute) {
            return $this->urlHelper->getAbsoluteUrl($this->relativeUploaderDir.$fileName);
        }

        return $this->urlHelper->getRelativePath($this->relativeUploaderDir.$fileName);
    }
}