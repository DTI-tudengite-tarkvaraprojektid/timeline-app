export class Event {
    constructor(id, title, time, contentPath, contentSavePath, deletePath, imageUploadPath, privacy, fileUploadPath) {
        this.id = id
        this.title = title;
        this.time = time;
        this.private = privacy;
        this.contentPath = contentPath;
        this.contentSavePath = contentSavePath;
        this.imageUploadPath = imageUploadPath;
        this.fileUploadPath = fileUploadPath;
        this.deletePath = deletePath;
    }
}