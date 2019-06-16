import { Group } from "../timeline/Group";
const moment = require("moment");
moment.locale('et');

export default class GroupManager {
    constructor (rules, groupers) {
        this.rules = rules;
        this.groupers = groupers;
    }

    group(events) {
        for (let i = 0; i < this.rules.length; i++) {
            const rule = this.rules[i];
            if (rule.shouldGroup(events)) {
                let groups = this.createGroups(events);
                return groups;
            }
        }
        return false;
    }

    createGroups(events) {
        for (let i = 0; i < this.groupers.length; i++) {
            const grouper = this.groupers[i];
            if (grouper.canGroup(events)) {
                console.log(events);
                let groups = grouper.getGroups(events);
                return groups;
            }
        }
        return false;
    }

    getSimpleGroups(events) {
        let startTime = events[0].time;
        let endTime = events[events.length - 1].time;

        let startGroup = new Group(moment(startTime).format('Do MMM YYYY'), events, startTime, endTime);
        let endGroup = new Group(moment(endTime).format('Do MMM YYYY'), [], endTime, endTime);
        return [startGroup, endGroup]
    }
}