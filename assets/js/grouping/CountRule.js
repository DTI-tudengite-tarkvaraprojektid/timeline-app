import BaseRule from "./BaseRule";

export default class CountRule extends BaseRule {
    constructor(count) {
        super();
        this.count = count;
    }

    shouldGroup(events) {
        return events.length > this.count;
    }

    getGroupers() {

    }
}