
export default class BaseGrouper {

    canGroup(events) {
        return true;
    }

    getGroups(events) {
        return [new Group('', [events], events[0].time, events[events.length - 1].time)];
    }
}