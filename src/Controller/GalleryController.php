<?php

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Model\Content;
use Awurth\Slim\Helper\Controller\Controller;

/**
 *
 */
class GalleryController extends Controller
{

  public function gallery(Request $request, Response $response, $page = null)
  {
    if ($page == null){
      $page = 1;
    }
    $limit = 42;
    $skip = $limit * ($page - 1);
    $pics = Content::where('type','IMAGE');
    $pages = ceil($pics->count() / $limit) - 1;
    $pics = $pics->skip($skip)->limit($limit)->get();
    $groups = [];
    for ($i=0; $i < count($pics); $i++) { 
        $groups[$pics[$i]->event->timeline_id][] = $pics[$i];
    }

    return $this->render($response, 'app/gallery.twig', [
        'groups' => $groups,
        'page' => $page,
        'pages' => $pages
    ]);
  }

  public function showPics(Request $request, Response $response)
  {
      $pics = Content::where('type','IMAGE');
        return $this->render($response, 'app/userlist.twig', [
          'pics' => $pics
      ]);
  }

}
