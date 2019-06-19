<?php

namespace App\Parser;

use nadar\quill\Line;
use nadar\quill\InlineListener;

class FileBlot extends InlineListener
{
    public $wrapper = '<span class="event-file border p-1"><i class="fas fa-download mr-1"></i>{filename}</span>';
    /**
     * {@inheritDoc}
     */
    public function process(Line $line)
    {
        $file = $line->insertJsonKey('file');
        if ($file) {
            $this->updateInput($line, str_replace(['{filename}'], [$line->getLexer()->escape($file['name'])], $this->wrapper));
        }
    }
}
