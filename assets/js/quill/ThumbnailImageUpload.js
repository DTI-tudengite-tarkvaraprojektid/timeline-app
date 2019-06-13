import { ImageUpload } from 'quill-image-upload';
import ImageUploadTooltip from './ImageUploadTooltip'

class ThumbnailImageUpload extends ImageUpload {

    /**
	 * Instantiate the module given a quill instance and any options
	 * @param {Quill} quill
	 * @param {Object} options
	 */
	constructor(quill, options = {}) {
        super(quill, options);
        console.log(options)
        this.tooltip = new ImageUploadTooltip(this.quill, this.quill.root, options.images, 
            (input) => {
                this.uploadImage(input);
            },
            (input) => {
                this.insert(input);
            }
        );
        this.tooltip.position({left:0, right:0, top:0, bottom:0})
    }
    
    uploadImage(input) {
        const file = input.files[0];

        // file type is only image.
        if (/^image\//.test(file.type)) {
            const checkBeforeSend =
                this.options.checkBeforeSend || this.checkBeforeSend.bind(this);
            checkBeforeSend(file, this.sendToServer.bind(this));
        } else {
            console.warn('You could only upload images.');
        }
    }
    /**
	 * Select local image
	 */
	selectLocalImage() {
        this.tooltip.show();
    }
    
	insert(data) {
        console.log(data);
        const index =
			(this.quill.getSelection() || {}).index || this.quill.getLength();
        if (this.tooltip.thumbnailCheckbox.checked) {
            
            this.quill.insertEmbed(index, 'thumbnailImage', data, 'user');
        } else {
            this.quill.insertEmbed(index, 'image', data.path, 'user');
        }
		
        this.tooltip.hide();
	}
}
export default ThumbnailImageUpload;