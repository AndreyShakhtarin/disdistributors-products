<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 10.10.16
 * Time: 19:57
 */

namespace App\EducationBundle\UploadFile;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function uploadImages(UploadedFile $file)
    {

        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->targetDir, $fileName);

        return $fileName;
    }

    public function uploadSwitcher($fileName)
    {

        $typeFile = $fileName->getClientOriginalExtension();

        $textTypes = array('/.doc/'=>'document',
            '/fb2/'=>'document',
            '/readme/'=>'document',
            '/text/'=>'document',
            '/djv/' =>'document',
            '/docm/' =>'document',
            '/docx/' =>'document',
            '/ibooks/' =>'document',
            '/pdf/' =>'document',
            '/mobi/' =>'document',
            '/flac/' => 'audio',
            '/midi/' => 'audio',
            '/mp3/' => 'audio',
            '/3gp/' => 'video',
            '/3gp2/' => 'video',
            '/avi/' => 'video',
            '/flv/' => 'video',
            '/mkv/' => 'video',
            '/mpg/' => 'video',
            '/mpg2/' => 'video',
            '/wmv/' => 'video',

        );
        foreach($textTypes as $textType=>$type)
        {
            if(preg_match($textType, $typeFile))
            {
                $file = $type;
            }
        }
        switch ($file)
        {
            case 'document':
                $fileName = $this->get('app.documents_product_uploader')->uploadImages($fileName);
                echo $file;
                return $fileName;
            case 'audio':
                $fileName = $this->get('app.audios_product_uploader')->uploadImages($fileName);
                echo $file;
                return $fileName;
            case 'video':
                $fileName = $this->get('app.videos_product_uploader')->uploadImages($fileName);
                echo $file;
                return $fileName;
            default: return null;
        }

    }
}