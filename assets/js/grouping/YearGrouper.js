import BaseGrouper from "./BaseGrouper";
import { Group } from "../timeline/Group";

export default class YearGrouper extends BaseGrouper {
    
    constructor (span) {
        super();
        this.span = span; // 5
    }

    canGroup(events) {
        return events[0].time.getFullYear() != events[events.length - 1].time.getFullYear()
    }

    getGroups(events) {

        let startYear = events[0].time.getFullYear();
        let endYear = events[events.length - 1].time.getFullYear();
        let totalYears = endYear - startYear + 2 // + 2 because we want to include both, start and end year, as well
        var step = Math.floor(this.getBaseLog(this.span, totalYears)) + 1;
        console.log("Step: " + step);

        let timelineStart = new Date(startYear, 0, 1).getTime();
        let timelineEnd = new Date(endYear + 1, 0, 1).getTime();
        let timelineDelta = timelineEnd - timelineStart;

        let groups = [];
        let lastYear = 0;
        let group = null;

        events.forEach(event => {
            let year = event.time.getFullYear();
            console.log('year: ' + year + ' | Lastyear: ' + lastYear);
            if (year >= lastYear + step) {
                if (group != null) {
                    let tempYear = lastYear + step;
                    // If empty years in between, add them as empty groups
                    console.log('Checking if empty ');
                    // 2012
                    // 2015
                    while (year > tempYear + step) {
                        groups.push(new Group(tempYear, [], new Date(tempYear, 0, 1), new Date(tempYear + step, 0, 1)));
                        lastYear = tempYear;
                        tempYear += step;
                    }
                    
                }
                if (lastYear == 0) {
                    lastYear = year;
                } else {
                    lastYear = lastYear + step;
                }
                group = new Group(lastYear, [], new Date(lastYear, 0, 1), new Date(year + step, 0, 1));
                groups.push(group);
            }
            group.events.push(event);
        });
        // Add the end year
        groups.push(new Group(lastYear + step, [], new Date(lastYear + step, 0, 1), new Date(lastYear + step, 0, 1)));

        return groups;
    }

    getBaseLog(x, y) {
        return Math.log(y) / Math.log(x);
    }
}