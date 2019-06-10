export class Group {
    constructor(name, events, startTime, endTime, onClick = null) {
        this.name = name;
        this.startTime = startTime;
        this.endTime = endTime;
        this.events = events;
        this.onClick = onClick;
    }
}
