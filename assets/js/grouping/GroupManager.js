import { Group } from "../timeline/Group";
const moment = require("moment");
moment.locale('et');

export default class GroupManager {
    constructor (rules) {
        this.rules = rules;
    }

    group(lastGroup) {
        for (let i = 0; i < this.rules.length; i++) {
            const rule = this.rules[i];
            if (rule.shouldGroup(lastGroup)) {
                let groups = this.createGroups(lastGroup, rule.groupers);
                return groups;
            }
        }
        return false;
    }

    createGroups(lastGroup, groupers) {
        for (let i = 0; i < groupers.length; i++) {
            const grouper = groupers[i];
            if (grouper.canGroup(lastGroup)) {
                console.log(lastGroup);
                let groups = grouper.getGroups(lastGroup);
                return groups;
            }
        }
        return false;
    }

    getSimpleGroups(lastGroup) {
        let startTime = lastGroup.startTime;
        let endTime = lastGroup.endTime;

        let startGroup = new Group(moment(startTime).format('Do MMM YYYY'), lastGroup.events, startTime, endTime);
        let endGroup = new Group(moment(endTime).format('Do MMM YYYY'), [], endTime, endTime);
        return [startGroup, endGroup]
    }
}