
export default class BaseGrouper {

    canGroup(group) {
        return true;
    }

    getGroups(group) {
        return [group];
    }
}