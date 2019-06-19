export class BaseEvent {
    constructor(title, time) {
        this.title = title;
        this.time = time;
    }

    static copyEvent(event) {
        return new BaseEvent(
            event.title,
            new Date(event.time.getTime())
        );
    }
}