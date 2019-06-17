import BaseGrouper from "./BaseGrouper";
import { Group } from "../timeline/Group";

export default class YearGrouper extends BaseGrouper {
    
    constructor (maxGroups) {
        super();
        this.maxGroups = maxGroups;
    }

    canGroup(lastGroup) {
        return lastGroup.startTime.getFullYear() != lastGroup.endTime.getFullYear()
    }

    getGroups(lastGroup) {

        let startYear = lastGroup.startTime.getFullYear();
        let endYear = lastGroup.endTime.getFullYear();
        let totalYears = endYear - startYear + 2 // + 2 because we want to include both, start and end year, as well
        var step = Math.ceil(totalYears / this.maxGroups);
        console.log("Step: " + step);

        let timelineStart = new Date(startYear, 0, 1).getTime();
        let timelineEnd = new Date(endYear + 1, 0, 1).getTime();
        //let timelineDelta = timelineEnd - timelineStart;

        let startOffset = (totalYears % step);
        if (startOffset == 0) {
            startOffset = step;
        }
        console.log("totalYears = " + totalYears + " | start offset: " + startOffset + " | startYear: " + startYear);
        let groups = [];
        let lastYear = startYear;
        let lastEndYear = lastYear + startOffset;
        let group = new Group(this.getNameByYears(lastYear, lastEndYear), [], new Date(lastYear, 0, 1), new Date(lastEndYear - 1, 11, 31));
        groups.push(group);

        lastGroup.events.forEach(event => {
            let year = event.time.getFullYear();
            console.log('year: ' + year + ' | Lastyear: ' + lastYear + " | LastEndYear: " + lastEndYear);
            if (year >= lastEndYear) {

                let tempYear = lastEndYear;
                let tempEndYear = lastEndYear + step;
                // If empty years in between, add them as empty groups
                console.log('Checking if empty ');
                while (year > tempEndYear) {
                    console.log('Next group would be empty. Filling it up... ');
                    
                    groups.push(new Group(this.getName(tempYear, step), [], new Date(tempYear, 0, 1), new Date(tempEndYear - 1, 11, 31)));
                    lastYear = tempEndYear;
                    lastEndYear = tempEndYear;
                    tempYear += step;
                    tempEndYear += step
                }

                lastYear = lastEndYear;
                lastEndYear = lastYear + step;

                group = new Group(this.getName(lastYear, step), [], new Date(lastYear, 0, 1), new Date(lastEndYear - 1, 11, 31));
                groups.push(group);
            }
            group.events.push(event);
        });
        // Add the end year
        //groups.push(new Group(lastYear + step, [], new Date(lastYear + step, 0, 1), new Date(lastYear + step - 1, 11, 31)));

        return groups;
    }

    getName(startYear, step) {
        if (step > 1) {
            return startYear + ' - ' + (startYear + step - 1);
        } else {
            return startYear;
        }
    }

    getNameByYears(startYear, endYear) {
        return this.getName(startYear, endYear - startYear);
    }
}