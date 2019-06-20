<?php

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Model\Content;
use Awurth\Slim\Helper\Controller\Controller;

/**
 *
 */
class FilesController extends Controller
{

  public function files(Request $request, Response $response, $page = null, $searchquery = null)
  {
    if ($page == null) {
      $page = 1;
    }
    $limit = 20;
    $skip = $limit * ($page - 1);

    

    if($searchquery == NULL){
      $files = Content::where('type','FILE');
    } else {
      $files = Content::search($searchquery)->where('type','FILE');
    }
    if ($this->auth->check()) {
      $files = Content::where('type','FILE');
    } else {
      $files = Content::where('type','FILE')->whereHas('event', function ($query) {
      $query->where('private', 0);
      });
  }
    $pages = ceil($files->count() / $limit) - 1;
    $files = $files->skip($skip)->limit($limit)->get();
    $groups = [];
    for ($i=0; $i < count($files); $i++) {
      $files[$i]->content = json_decode($files[$i]->content);
      $files[$i]->content->path = $this->settings['file_upload_uri'] . '/' . $files[$i]->content->path;
      $groups[$files[$i]->event->timeline_id][] = $files[$i];
    }

    return $this->render($response, 'app/files.twig', [
        'groups' => $groups,
        'page' => $page,
        'pages' => $pages
    ]);
  }


  public function getFile(Request $request, Response $response, $id, $fileId) {
      $file = Content::find($fileId);
      if ($file != null) {
          return $response
              ->withHeader("Content-Type", mime_content_type($file->content))
              ->write(file_get_contents($this->settings['upload_path'] . '/' . $file->content));
      }
      throw $this->notFoundException();
  }

/*  public function getfilename(Request $request, Response $response, $id, $fileId) {
      $file = Content::where($fileId)->get('content');
      if ($file != null) {
          return $file;
      }
      throw $this->notFoundException();
  }*/
  
}
