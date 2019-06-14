import Image from 'quill/formats/image';

class CustomImageBlot extends Image {
    static create(value) {
        let node = super.create();
        node.setAttribute('src', this.sanitize(value.path));
        node.dataset.id = this.sanitize(value.id);
        node.dataset.thumbnail = this.sanitize(value.thumbnail);
        return node;
    }

    static value(domNode) {
        return {
            id: domNode.dataset.id,
            thumbnail: domNode.dataset.thumbnail,
            path: domNode.getAttribute('src')
        }
    }
}
CustomImageBlot.blotName = 'customImage';
CustomImageBlot.tagName = 'img';
CustomImageBlot.className = 'event-image'

export default CustomImageBlot;