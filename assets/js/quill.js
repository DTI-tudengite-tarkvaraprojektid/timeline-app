import Quill from 'quill/core';

import Toolbar from 'quill/modules/toolbar';
import Snow from 'quill/themes/snow';

import { AlignClass } from 'quill/formats/align';
import { SizeClass } from 'quill/formats/size';
import Bold from 'quill/formats/bold';
import Italic from 'quill/formats/italic';
import Underline from 'quill/formats/underline';
import Strike from 'quill/formats/strike';
import Header from 'quill/formats/header';
import Link from 'quill/formats/link';
import List, { ListItem } from 'quill/formats/list';
import Image from 'quill/formats/image';
import Video from 'quill/formats/video';
import ThumbnailImageBlot from './quill/ThumbnailImageBlot';
import ThumbnailImageUpload from './quill/ThumbnailImageUpload';

//import { ImageUpload } from 'quill-image-upload';

Image.className = 'img-fluid';

Quill.register({
    'modules/toolbar': Toolbar,
    'themes/snow': Snow,
    'formats/align': AlignClass,
    'formats/size': SizeClass,
    'formats/bold': Bold,
    'formats/italic': Italic,
    'formats/underline': Underline,
    'formats/strike': Strike,
    'formats/header': Header,
    'formats/link': Link,
    'formats/list': List,
    'formats/list-item': ListItem,
    'formats/image': Image,
    'formats/thumbnailImage': ThumbnailImageBlot,
    'formats/video': Video,
    'modules/imageUpload': ThumbnailImageUpload
});


export default Quill;