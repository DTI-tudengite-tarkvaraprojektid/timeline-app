import BaseRule from "./BaseRule";

export default class SameDayRule extends BaseRule {
    constructor(daySpan, groupers) {
        super(groupers);
        this.daySpan = daySpan;
    }

    shouldGroup(group) {
        let count = group.events.length;
        for (let i = 0; i < count; i++) {
            if (i < count - 1) {
                const event = group.events[i];
                const nextEvent = group.events[i + 1];
                if (event.time.getTime() == nextEvent.time.getTime()) {
                    console.log("2 events on same date! will group...");
                    return true;
                }
            }
            
        }
        return false;
    }
}