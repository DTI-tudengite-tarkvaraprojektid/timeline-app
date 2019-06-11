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
                ->write(file_get_contents($image->content));
        }
    }

    public function uploadImage(Request $request, Response $response, $id)
    {
        $factory = new FileUploadFactory(
            new PathResolver\Simple($this->settings['upload_path']), 
            new FileSystem\Simple(), 
            [
                new FileUpload\Validator\MimeTypeValidator(['image/png', 'image/jpg'])
            ],
            new FileUpload\FileNameGenerator\Random(32)
        );
        
        $fileupload = $factory->create($_FILES['image'], $_SERVER);

        list($files, $headers) = $fileupload->processAll();

        if ($files[0]->completed) {
            $event = Event::find($id);
            $content = new Content();
            $content->type = 'IMAGE';
            $content->content = $files[0]->getRealPath();
            $event->content()->save($content);
    
            return $response->withJson([
                'message' => 'Image uploaded',
                'path' => $this->path('get-image', ['id' => $id, 'image' => $content->id])
            ]);
        } else {
            return $response->withStatus(400)->withJson([
                'message' => 'Failed to upload image'
            ]);
        }

        
    }
}
