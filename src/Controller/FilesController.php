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

  public function files(Request $request, Response $response, $searchquery=NULL)
  {
    // code...
    if($searchquery == NULL){
      $files = Content::where('type','FILE')->get();
      $groups = [];
      for ($i=0; $i < count($files); $i++) {
          $files[$i]->content = json_decode($files[$i]->content);
          $files[$i]->content->path = $this->settings['file_upload_uri'] . '/' . $files[$i]->content->path;
          $groups[$files[$i]->event->timeline_id][] = $files[$i];
      }

      return $this->render($response, 'app/files.twig', [
          'groups' => $groups
      ]);
    } else {
      $files = Content::search($searchquery)->where('type','FILE')->get();
      $groups = [];
      for ($i=0; $i < count($files); $i++) {
          $files[$i]->content = json_decode($files[$i]->content);
          $files[$i]->content->path = $this->settings['file_upload_uri'] . '/' . $files[$i]->content->path;
          $groups[$files[$i]->event->timeline_id][] = $files[$i];
      }

      return $this->render($response, 'app/files.twig', [
          'groups' => $groups
      ]);
    }
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
