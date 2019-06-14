//import Image from 'quill/formats/image';
import EmbedBlock from 'quill/blots/block';

class FileBlot extends EmbedBlock {
    static create(value) {
        let node = super.create();
        console.log('create')
        node.href = value.path;
        node.dataset.id = value.id;
        node.download = value.name;
        node.textContent = value.name;
        return node;
    }



    static formats(node) {
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