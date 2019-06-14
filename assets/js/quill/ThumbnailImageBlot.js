import Image from 'quill/formats/image';

class ThumbnailImageBlot extends Image {
    static create(value) {
        let node = super.create();
        node.classList.add('img-thumbnail', 'm-1');
        node.setAttribute('src', this.sanitize(value.thumbnail));
        node.dataset.id = value.id;
        node.dataset.path = this.sanitize(value.path);
        return node;
    }

    static value(domNode) {
        return {
            id: domNode.dataset.id,
            thumbnail: domNode.getAttribute('src'),
            path: domNode.dataset.path
        }
    }
}
ThumbnailImageBlot.blotName = 'thumbnailImage';
ThumbnailImageBlot.tagName = 'img';
ThumbnailImageBlot.className = 'event-thumbnail'

export default ThumbnailImageBlot;