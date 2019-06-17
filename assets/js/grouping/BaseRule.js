export default class BaseRule {
    constructor(groupers) {
        this.groupers = groupers
    }
    shouldGroup(group) {
        return false;
    }
}