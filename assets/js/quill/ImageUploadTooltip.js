import Tooltip from 'quill/ui/tooltip';

class ImageUploadTooltip extends Tooltip {
    constructor(quill, boundsContainer, imagesPath, callback, existingCallback) {
        super(quill, boundsContainer);
        this.fileInput = this.root.querySelector('#image-upload-input');
        this.thumbnailCheckbox = this.root.querySelector('input[type="checkbox"]');
        this.callback = callback;
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

    show() {
        super.show();
        //this.root.classList.add('ql-editing');
    }

    hide() {
        super.hide();
        $(this.fileInput).val('');
        //this.root.classList.add('ql-editing');
    }
}
ImageUploadTooltip.TEMPLATE = [
    '<span class="my-2 mr-2">Lisa uus pilt:</span>',
    '<input id="image-upload-input" type="file" name="image">',
    '<br>',
    '<div class="d-flex">',
        '<div class="form-check form-check-inline">',
            '<input class="form-check-input" id="show-as-thumbnail" type="checkbox" name="thumbnail" value="1" checked>',
            '<label class="form-check-label" for="show-as-thumbnail">Näita pisipildina</label>',
        '</div>',
        '<a class="btn btm-sm close-button ml-auto" href="#" >Tagasi</a>',
        '<a class="btn btm-sm save-button" href="#">Lae üles</a>',
    '</div>'
].join('');

export default ImageUploadTooltip;