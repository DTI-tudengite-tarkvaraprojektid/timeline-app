import { Group } from "./Group";
import { Timeline } from "./Timeline";
import { GroupedTimeline } from "./GroupedTimeline";

export class TimelineManager {
    constructor(timeline, subTimeline, eventManager, events = []) {
        this.timelineSelector = timeline;
        this.timeline = $(timeline);
        this.subTimelineSelector = subTimeline;
        this.subTimeline = $(subTimeline);
        this.zoom = 0;
        this.events = events;
        this.eventManager = eventManager;

        //this.subTimeline.hide();

        this.initEvents();
    }


    initEvents() {
        this.subTimeline.on('click', (e) => {
            e.preventDefault();
            $('#new-event-modal').modal('show');
        });
    }

    render() {
        this.timeline.empty();
        let startYear = this.events[0].time.getFullYear();
        let endYear = this.events[this.events.length - 1].time.getFullYear();
        let totalYears = endYear - startYear + 2 // + 2 because we want to include both, start and end year, as well
        
        let timelineStart = new Date(startYear, 0, 1).getTime();
        let timelineEnd = new Date(endYear + 1, 0, 1).getTime();
        let timelineDelta = timelineEnd - timelineStart;
        
        let groups = [];
        let lastYear = 0; // 2018 year 2020
        let group = null;

        this.events.forEach(event => {
            let year = event.time.getFullYear();
            if (lastYear != year) {
                if (group != null) {
                    let tempYear = lastYear;
                    // If empty years in between, add them as empty groups
                    while (tempYear < year - 1) {
                        tempYear++;
                        groups.push(new Group(tempYear, [], new Date(tempYear, 0, 1), new Date(tempYear + 1, 0, 1)));
                    }
                }
                group = new Group(year, [], new Date(year, 0, 1), new Date(year + 1, 0, 1));
                groups.push(group);
                lastYear = year;
            }
            group.events.push(event);
        });
        // Add the end year
        groups.push(new Group(lastYear + 1, [], new Date(lastYear + 1, 0, 1), new Date(lastYear + 1, 0, 1)));

        // Create the timeline
        let timeline = new GroupedTimeline(this.timelineSelector, groups, (group, nextGroup) => {
            this.renderSubTimeline(group, nextGroup);
        });
        timeline.render();
    }

    renderSubTimeline(group, nextGroup) {
        this.subTimeline.parent().collapse('show');
        //this.subTimeline.empty();
        //$('#sub-timeline-start').text(year);
        //$('#sub-timeline-end').text(year + 1);

        let timeline = new Timeline(this.subTimelineSelector, group, nextGroup, (event) => {
            this.eventManager.showEvent(event);
        });
        timeline.render();
    }

    getTimelinePoint(index, title, description = null, left=0, width=null) {
        let timeline = $('<div></div>');
        timeline.addClass('timeline-point point-event')
            .prop('title', title)
            .data('event', index)
            .data('toggle', 'tooltip')
            .data('placement', 'bottom')
            .css('left', left + '%')

        if (width !== null) {
            timeline.css('width', width + '%');
        }

        let header = $('<div></div>');
        header.addClass('point-header');

        timeline.append(header);
        timeline.tooltip();
        return timeline;
    }

}
