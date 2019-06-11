export class Event {
    constructor(id, title, time, contentPath, contentSavePath, deletePath, imageUploadPath) {
        this.id = id
        this.title = title;
        this.time = time;
        this.contentPath = contentPath;
        this.contentSavePath = contentSavePath;
        this.imageUploadPath = imageUploadPath;
        this.deletePath = deletePath;
    }
}