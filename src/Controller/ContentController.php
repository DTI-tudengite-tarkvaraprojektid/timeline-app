<?php

namespace App\Controller;

use FileUpload;
use App\Model\Event;
use App\Model\Content;
use nadar\quill\Debug;
use nadar\quill\Lexer;
use Slim\Http\Request;
use Slim\Http\Response;
use App\Parser\FileBlot;
use App\Parser\SizeBlot;
use FileUpload\FileSystem;
use App\Parser\CustomImage;
use FileUpload\PathResolver;
use App\Parser\ThumbnailImage;
use FileUpload\FileUploadFactory;
use Respect\Validation\Validator as V;
use Awurth\Slim\Helper\Controller\Controller;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;


/**
 * @property \Awurth\SlimValidation\Validator validator
 * @property \Cartalyst\Sentinel\Sentinel     auth
 */
class ContentController extends Controller
{
    public function get(Request $request, Response $response, $id)
    {
        $event = Event::find($id);

        return $response->withJson([
            'content' => json_decode($event->content)
        ]);
    }

    public function getHtml(Request $request, Response $response, $id)
    {
        $event = Event::find($id);
        if ($event->content) {
            $content = json_decode($event->content, true);
            if (count($content) > 10) {
                $content = array_slice($content, 0, 4);
                $content[] = [
                    'insert' => '[Jätkub...]'
                ];
            }
            $lexer = new Lexer($content);
            $lexer->registerListener(new ThumbnailImage);
            $lexer->registerListener(new CustomImage);
            $lexer->registerListener(new FileBlot);
            $lexer->registerListener(new SizeBlot);
            $lexer->render();
            //$debug = new Debug($lexer);
            
            return $response->write($lexer->render()/* $debug->debugPrint() */);
        }
        return $response;
    }

    public function save(Request $request, Response $response, $id)
    {
        $event = Event::find($id);
        $event->content = $request->getBody();
        $deltas = json_decode($request->getBody());
        $content = [];

        foreach ($deltas as $op) {
            if (isset($op->insert->file)) {
                $content[] = $op->insert->file->id;
            } else if (isset($op->insert->thumbnailImage)) {
                $content[] = $op->insert->thumbnailImage->id;
            } else if (isset($op->insert->customImage)) {
                $content[] = $op->insert->customImage->id;
            }
        }

        $event->contents()->whereNotIn('id', $content)->get()->each(function($content) {
            $content->delete();
        });

        $event->save();
        return $response->withJson([
            'message' => 'Content saved'
        ]);
    }

    public function getImage(Request $request, Response $response, $id, $imageId) {
        $image = Content::find($imageId);
        if ($image != null) {
            return $response
                ->withHeader("Content-Type", mime_content_type($image->content))
                ->write(file_get_contents($this->settings['upload_path'] . '/' . $image->content));
        }
        throw $this->notFoundException();
    }

    public function getThumb(Request $request, Response $response, $id, $imageId) {
        $image = Content::find($imageId);
        if ($image != null) {
            return $response
                ->withHeader("Content-Type", mime_content_type($image->content))
                ->write(file_get_contents($this->settings['thumbnail_path'] . '/' . $image->content));
        }
    }

    public function uploadImage(Request $request, Response $response, $id)
    {
        $factory = new FileUploadFactory(
            new PathResolver\Simple($this->settings['upload_path']),
            new FileSystem\Simple(),
            [
                new FileUpload\Validator\MimeTypeValidator(['image/gif','image/png', 'image/jpg', 'image/jpeg', 'image/bmp', 'image/x-windows-bmp'])
            ],
            new FileUpload\FileNameGenerator\Random(32)
        );

        $fileupload = $factory->create($_FILES['image'], $_SERVER);


        list($files, $headers) = $fileupload->processAll();

        if ($files[0]->completed) {

            $thumb = $this->createThumbnail($files[0], $this->settings['thumbnail_path'] . '/' . $files[0]->getFilename(), 200);

            $event = Event::find($id);
            $content = new Content();
            $content->type = 'IMAGE';
            $content->content = $files[0]->getFilename();
            $event->contents()->save($content);

            return $response->withJson([
                'message' => 'Image uploaded',
                'id' => $content->id,
                'thumbnail-path' => $this->path('get-thumb', ['id' => $id, 'image' => $content->id]),
                'path' => $this->path('get-image', ['id' => $id, 'image' => $content->id])
            ]);
        } else {
            return $response->withStatus(400)->withJson([
                'message' => 'Pildi üleslaadimine ebaõnnestus: ' . $files[0]->error
            ]);
        }


    }

    public function uploadFile(Request $request, Response $response, $id)
    {
        $factory = new FileUploadFactory(
            new PathResolver\Simple($this->settings['file_upload_path']),
            new FileSystem\Simple(),
            [],
            new FileUpload\FileNameGenerator\Random(32)
        );

        $fileupload = $factory->create($_FILES['file'], $_SERVER);


        list($files, $headers) = $fileupload->processAll();

        if ($files[0]->completed) {
            $event = Event::find($id);
            $content = new Content();
            $content->type = 'FILE';
            $content->content = json_encode([
                'path' => $files[0]->getFilename(),
                'name' => $files[0]->getClientFileName()
            ]);
            $event->contents()->save($content);

            return $response->withJson([
                'message' => 'File uploaded',
                'id' => $content->id,
                'name' => $files[0]->getClientFileName(),
                'path' => $this->settings['file_upload_uri'] . '/' . $files[0]->getFilename()
            ]);
        } else {
            return $response->withStatus(400)->withJson([
                'message' => 'Faili üleslaadimine ebaõnnestus: ' . $files[0]->error
            ]);
        }


    }

    public function createThumbnail($file, $thumbnailpath, $size){

      $imageFileType = $file->getMimeType();
        if($imageFileType == "image/jpg" || $imageFileType == "image/jpeg"){
          $myTempImage = imagecreatefromjpeg($file->getRealPath());
        }
        else if ($imageFileType == "image/png"){
          $myTempImage = imagecreatefrompng($file->getRealPath());
        }
        else if ($imageFileType == "image/gif"){
          $myTempImage = imagecreatefromgif($file->getRealPath());
        }
        else if ($imageFileType == "image/bmp" || $imageFileType == "image/x-windows-bmp"){
          $myTempImage = imagecreatefrombmp($file->getRealPath());
        }

        $imageWidth = imagesx($myTempImage);
        $imageHeight = imagesy($myTempImage);
        if($imageWidth > $imageHeight){
            $cutSize = $imageHeight;
            $cutX = round(($imageWidth-$cutSize) / 2);
            $cutY = 0;
        } else {
            $cutSize = $imageWidth;
            $cutX = 0;
            $cutY = (($imageHeight-$cutSize) / 2);
        }
        //loome pildiobjeki
        $myThumbnail = imagecreatetruecolor($size, $size);
        imagecopyresampled($myThumbnail, $myTempImage, 0, 0, $cutX, $cutY, $size, $size, $cutSize, $cutSize);
        //salvestan
        if ($imageFileType == "image/jpg" || $imageFileType == "image/jpeg"){
            imagejpeg($myThumbnail, $thumbnailpath, 90);
        }
        else if ($imageFileType == "image/png"){
            imagepng($myThumbnail, $thumbnailpath, 6);
        }
        else if ($imageFileType == "image/gif"){
            imagegif($myThumbnail, $thumbnailpath);
        }
        else if ($imageFileType == "image/bmp" || $imageFileType == "image/x-windows-bmp"){
            imagebmp($myThumbnail, $thumbnailpath);
        }
    }
}
