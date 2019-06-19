import { BaseEvent } from "./BaseEvent";

export class Event extends BaseEvent {
    constructor(id, title, time, contentPath, contentSavePath, deletePath, imageUploadPath, privacy, fileUploadPath) {
        super(title, time);
        this.id = id
        this.private = privacy;
        this.contentPath = contentPath;
        this.contentSavePath = contentSavePath;
        this.imageUploadPath = imageUploadPath;
        this.fileUploadPath = fileUploadPath;
        this.deletePath = deletePath;
    }

    static copyEvent(event) {
        return new Event(
            event.id,
            event.title,
            new Date(event.time.getTime()),
            event.contentPath,
            event.contentSavePath,
            event.deletePath,
            event.imageUploadPath,
            event.privacy,
            event.fileUploadPath
        );
    }
}