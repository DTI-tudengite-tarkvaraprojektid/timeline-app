<?php

namespace App\Parser;

use nadar\quill\Line;
use nadar\quill\BlockListener;
use nadar\quill\InlineListener;

class SizeBlot extends InlineListener
{
    //public $sizes = ['small', 'large', 'huge'];

    /**
     * {@inheritDoc}
     */
    public function process(Line $line)
    {
        $size = $line->getAttribute('size');
        if ($size) {
            $this->updateInput($line, '<span class="ql-size-' . $size . '">'.$line->getInput().'</span>');
        }
    }
    
}
