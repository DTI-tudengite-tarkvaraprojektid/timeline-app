//import Image from 'quill/formats/image';
import Embed from 'quill/blots/embed';


class FileBlot extends Embed {
    static create(value) {
        let node = super.create();
        console.log('create')
        node.href = value.path;
        node.dataset.id = value.id;
        node.download = value.name;
        node.classList.add('border', 'p-1');
        $(node).html('<i class="fas fa-download mr-1"></i>' + value.name + '');
        return node;
    }



    static value(node) {
        return {
            id: node.dataset.id,
            name: node.download,
            path: node.href
        }
    }
}
FileBlot.blotName = 'file';
FileBlot.tagName = 'a';
FileBlot.className = 'event-file';

export default FileBlot;