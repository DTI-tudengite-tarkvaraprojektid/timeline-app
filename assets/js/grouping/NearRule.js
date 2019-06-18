import BaseRule from "./BaseRule";

export default class NearRule extends BaseRule {
    constructor(minDistance, groupers) {
        super(groupers);
        this.minDistance = minDistance;
    }

    shouldGroup(group) {
        let delta = group.endTime.getTime() - group.startTime.getTime();
        console.log('NearRule - delta: ' + delta);
        let count = group.events.length;
        for (let i = 0; i < count; i++) {
            if (i < count - 1) {
                const event = group.events[i];
                const nextEvent = group.events[i + 1];
                const eventsDelta = nextEvent.time.getTime() - event.time.getTime();
                console.log('NearRule - eventsDelta: ' + eventsDelta);
                console.log('NearRule - part: ' + (eventsDelta / delta));
                if (eventsDelta / delta < this.minDistance) {
                    console.log("2 events near each other! will group...");
                    return true;
                }
            }
            
        }
        return false;
    }
}