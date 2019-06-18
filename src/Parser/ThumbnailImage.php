<?php

namespace App\Parser;

use nadar\quill\Line;
use nadar\quill\InlineListener;

class ThumbnailImage extends InlineListener
{
    public $wrapper = '<img src="{src}" alt="" class="img-thumbnail m-1 event-thumbnail" />';

    /**
     * {@inheritDoc}
     */
    public function process(Line $line)
    {
        $embedUrl = $line->insertJsonKey('thumbnailImage');
        if ($embedUrl) {
            $this->updateInput($line, str_replace(['{src}'], [$line->getLexer()->escape($embedUrl['thumbnail'])], $this->wrapper));
        }
    }
}
