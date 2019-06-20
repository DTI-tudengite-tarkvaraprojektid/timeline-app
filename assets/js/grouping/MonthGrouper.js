import BaseGrouper from "./BaseGrouper";
import { Group } from "../timeline/Group";
const moment = require("moment");
moment.locale('et');

export default class MonthGrouper extends BaseGrouper {
    
    constructor (minEvents) {
        super();
        this.minEvents = minEvents;
    }

    canGroup(lastGroup) {
        return lastGroup.startTime.getFullYear() == lastGroup.endTime.getFullYear() && lastGroup.events.length > this.minEvents
    }

    getGroups(lastGroup) {

        let year = lastGroup.startTime.getFullYear();

        let startMonth = lastGroup.startTime.getMonth();
        let endMonth = lastGroup.endTime.getMonth();
        //let timelineDelta = timelineEnd - timelineStart;

        let groups = [];
        let lastMonth = startMonth;
        // Create first month
        let group = group = new Group(moment(lastMonth + 1, 'M').format('MMMM'), [], new Date(year, lastMonth, 1), new Date(year, lastMonth + 1, 1));
        groups.push(group);

        lastGroup.events.forEach(event => {
            let month = event.time.getMonth();
            if (month > lastMonth) {
                let tempMonth = lastMonth + 1;
                while (month > tempMonth) {
                    groups.push(new Group(moment(tempMonth + 1, 'M').format('MMMM'), [], new Date(year, tempMonth, 1), new Date(year, tempMonth + 1, 1)));
                    lastMonth = tempMonth;
                    tempMonth++;
                }

                    
                lastMonth++;
                group = new Group(moment(lastMonth + 1, 'M').format('MMMM'), [], new Date(year, lastMonth, 1), new Date(year, lastMonth + 1, 1));
                groups.push(group);
            }
            group.events.push(event);
        });
        while (lastMonth < endMonth) {
            lastMonth++;
            groups.push(new Group(moment(lastMonth + 1, 'M').format('MMMM'), [], new Date(year, lastMonth, 1), new Date(year, lastMonth + 1, 1)));
                    
        }
        //groups.push(new Group(year + step, [], new Date(lastYear + step, 0, 1), new Date(lastYear + step, 0, 1)));

        return groups;
    }

    getBaseLog(x, y) {
        return Math.log(y) / Math.log(x);
    }
}