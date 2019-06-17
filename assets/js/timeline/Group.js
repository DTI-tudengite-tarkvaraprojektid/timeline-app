import { BaseEvent } from "./BaseEvent";

export class Group extends BaseEvent {
    constructor(name, events, startTime, endTime, onClick = null) {
        super(name, startTime);
        this.name = name;
        this.startTime = startTime;
        this.endTime = endTime;
        this.events = events;
        this.onClick = onClick;
    }
}
