<?php

namespace App\Controller;

use App\Model\Event;
use App\Model\Content;
use Slim\Http\Request;
use Slim\Http\Response;
use FileUpload;
use FileUpload\FileUploadFactory;
use FileUpload\FileSystem;
use Respect\Validation\Validator as V;
use FileUpload\PathResolver;
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

    public function save(Request $request, Response $response, $id)
    {
        $event = Event::find($id);
        $event->content = $request->getBody();
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

    public function getImages(Request $request, Response $response, $id) {
        $images = Event::find($id)->content()->where('type', 'IMAGE')->get();
        $data = [];
        foreach ($images as $image) {
            $temp = [
                'id' => $image->id,
                'thumbnail' => $this->path('get-thumb', ['id' => $id, 'image' => $image->id]),
                'path' => $this->path('get-image', ['id' => $id, 'image' => $image->id])
            ];
            $data[] = $temp;
        }
        return $response->withJson($data);
    }

    public function getThumb(Request $request, Response $response, $id, $imageId) {
        $image = Content::find($imageId);
        if ($image != null) {
            return $response
                ->withHeader("Content-Type", mime_content_type($image->content))
                ->write(file_get_contents($this->settings['thumbnail_path'] . '/' . $image->content));
        }
    }

    public function delete(Request $request, Response $response, $id, $contentId) {
        $content = Content::find($imageId);
        if ($content != null) {
            if ($content->type == 'IMAGE') {
                unlink($this->settings['thumbnail_path'] . '/' . $content->content);
                unlink($this->settings['upload_path'] . '/' . $content->content);
            } else if ($content->type == 'FILE') {
                $file = json_decode($content->content);
                unlink($this->settings['upload_path'] . '/' . $file['path']);
            }
            $content->delete();
        }
        return $response->withJson(['message' => 'Content deleted']);
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
            $event->content()->save($content);

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
            $event->content()->save($content);

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
