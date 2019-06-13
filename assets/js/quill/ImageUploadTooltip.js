import Tooltip from 'quill/ui/tooltip';

class ImageUploadTooltip extends Tooltip {
    constructor(quill, boundsContainer, imagesPath, callback, existingCallback) {
        super(quill, boundsContainer);
        this.fileInput = this.root.querySelector('#image-upload-input');
        this.thumbnailCheckbox = this.root.querySelector('input[type="checkbox"]');
        this.imageListElement = this.root.querySelector('#image-upload-list');
        this.imageList = null;
        this.callback = callback;
        this.existingCallback = existingCallback;
        this.imagesPath = imagesPath
        this.root.classList.add('ql-custom');
        this.listen();
    }

    listen() {
        $(this.root).find('.close-button').click((e) => {
            e.preventDefault();
            this.hide();
        });
        $(this.root).find('.save-button').click((e) => {
            e.preventDefault();
            this.callback(this.fileInput);
        });
    }

    loadImages() {
        console.log(this.imagesPath);
        $(this.imageListElement).html('');
        $.getJSON(this.imagesPath, (data) => {
            this.imageList = data;
            data.forEach((image, index) => {
                var element = $('<a></a>').addClass('existing-image-link').prop('href', index).append(
                    $('<img></img>')
                        .addClass('existing-image img-fluid img-thumbnail')
                        .data('path', image.path)
                        .prop('src', image.thumbnail)
                );
                $(this.imageListElement).append(
                    $('<div></div>')
                        .addClass('m-1')
                        .append(element)
                );

                element.click((e) => {
                    e.preventDefault();
                    this.existingCallback(image);
                });
            });
        });
    }

    show() {
        super.show();
        this.loadImages();
        //this.root.classList.add('ql-editing');
    }

    hide() {
        super.hide();
        $(this.fileInput).val('');
        //this.root.classList.add('ql-editing');
    }
}
ImageUploadTooltip.TEMPLATE = [
    '<p class="my-2">Lisa olemasolev pilt:</p>',
    '<div id="image-upload-list" class="d-flex flex-wrap">',
    '</div>',
    '<p class="my-2">Lisa uus pilt:</p>',
    '<input id="image-upload-input" type="file" name="image">',
    '<input type="checkbox" name="thumbnail" value="1" checked> Näita pisipildina',
    '<a class="btn btm-sm save-button" href="#">Lae üles</a>',
    '<a class="btn btm-sm close-button" href="#" >Tühista</a>',
].join('');

export default ImageUploadTooltip;