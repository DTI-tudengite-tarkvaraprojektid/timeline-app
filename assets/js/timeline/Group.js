import { BaseEvent } from "./BaseEvent";

export class Group extends BaseEvent {
    constructor(name, events, startTime, endTime, onClick = null, pointTime = null) {
        if (pointTime == null) {
            super(name, startTime);
        } else {
            super(name, pointTime);
        }
        this.name = name;
        this.startTime = startTime;
        this.endTime = endTime;
        this.events = events;
        this.onClick = onClick;
    }

    static copyEvent(event) {
        return new Group(
            event.name,
            event.events,
            new Date(event.startTime.getTime()),
            new Date(event.endTime.getTime()),
            event.onClick,
            new Date(event.time.getTime())
        );
    }
}
