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
import CustomImageBlot from './quill/CustomImageBlot';
import ThumbnailImageBlot from './quill/ThumbnailImageBlot';
import ThumbnailImageUpload from './quill/ThumbnailImageUpload';
import FileBlot from './quill/FileBlot';
import FileUpload from './quill/FileUpload';

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
    'formats/file': FileBlot,
    'formats/list': List,
    'formats/list-item': ListItem,
    'formats/customImage': CustomImageBlot,
    'formats/thumbnailImage': ThumbnailImageBlot,
    'formats/video': Video,
    'modules/imageUpload': ThumbnailImageUpload,
    'modules/fileUpload': FileUpload
});


export default function getQuill(element, event) {
    var toolbarOptions = [[{'size': []}, 'bold', 'italic', 'underline', 'strike'], [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }], [{ 'list': 'ordered'}, { 'list': 'bullet' }], ['link', 'image', 'video', 'file']];
    var quill = new Quill(element, {
        theme: 'snow',
        modules: {
            toolbar: toolbarOptions,
            imageUpload: {
                url: event.imageUploadPath,
                name: 'image', // custom form name
                // personalize successful callback and call next function to insert new url to the editor
                callbackOK: (serverResponse, next) => {
                    next({
                        id: serverResponse['id'],
                        path: serverResponse['path'],
                        thumbnail: serverResponse['thumbnail-path']
                    });
                },
                // personalize failed callback
                callbackKO: serverError => {
                    alert(JSON.parse(serverError.body).message);
                }
            },
            fileUpload: {
                url: event.fileUploadPath,
                name: 'file', // custom form name
                // personalize successful callback and call next function to insert new url to the editor
                callbackOK: (serverResponse, next) => {
                    next({
                        id: serverResponse['id'],
                        name: serverResponse['name'],
                        path: serverResponse['path'],
                    });
                },
                // personalize failed callback
                callbackKO: serverError => {
                    alert(JSON.parse(serverError.body).message);
                }
            }
        }
    });
    /* quill.on("text-change", (delta, oldDelta, source) => {
        if (source === "user") {
          let currrentContents = quill.getContents();
          console.log("delta: ");
          console.log(delta);
          console.log("currrentContents: ");
          console.log(currrentContents);
          console.log("oldDelta: ");
          console.log(oldDelta);
          let diff = oldDelta.diff(currrentContents);
          console.log("diff: ");
          console.log(diff);
        }
    }); */

    return quill;
}