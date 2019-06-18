import BaseRule from "./BaseRule";

export default class CountRule extends BaseRule {
    constructor(count, groupers) {
        super(groupers);
        this.count = count;
    }

    shouldGroup(group) {
        return group.events.length > this.count;
    }
}