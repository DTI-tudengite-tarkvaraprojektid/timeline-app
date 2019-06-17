import { Group } from "./Group";
import { Timeline } from "./Timeline";
import { GroupedTimeline } from "./GroupedTimeline";
import GroupManager from "../grouping/GroupManager";
import CountRule from "../grouping/CountRule";
import YearGrouper from "../grouping/YearGrouper";
const moment = require("moment");
moment.locale('et');

export class TimelineManager {
    constructor(timeline, subTimeline, eventManager, events = []) {
        this.timelineSelector = timeline;
        this.timeline = $(timeline);
        this.subTimelineSelector = subTimeline;
        this.subTimeline = $(subTimeline);
        this.zoom = 0;
        this.events = events;
        this.eventManager = eventManager;
        this.currentTimeline = null;

        //this.subTimeline.hide();

        this.initEvents();

        $('.deleteBtn').click((e) => {
            let id = $(e.target).attr('data-id');
            let name = $(e.target).attr('data-name');
            if(confirm(name)){

            };
        });
    }

    initEvents() {
        this.subTimeline.on('click', (e) => {
            e.preventDefault();
            $('#new-event-modal').modal('show');
        });
    }

    render() {
        
        this.renderTimeline(this.getEventsAsGroup());
        
        
        /* if (this.events.length < 5) { // TODO: change to larger number after testing
            this.renderTimelineUngrouped();
        } else {
            this.renderTimelineGroupedByYears();
        } */
    }

    renderTimeline(group, depth = 0, nextGroup = null) {
        console.log('Depth: ' + depth);
        //this.timeline.empty();

        var groupManager = new GroupManager([
            new CountRule(5)
        ],
        [
            new YearGrouper(10)
        ]);

        let groups = groupManager.group(group.events);

        var selector;
        if (depth > 0) {
            selector = this.subTimelineSelector;
            this.subTimeline.collapse('show');
        } else {
            selector = this.timelineSelector;
        }

        
        if (groups === false) {
            console.log('No groups generated. Creating ungrouped timeline');
            if (nextGroup == null) {
                groups = groupManager.getSimpleGroups(group.events);
                group = groups[0];
                nextGroup = groups[1];
            }
            var timeline = new Timeline(selector, group, nextGroup, (event, element) => {
                this.eventManager.showEvent(event, element);
            });
        } else {
            var timeline = new GroupedTimeline(selector, groups, (group, nextGroup) => {
                this.renderTimeline(group, depth + 1, nextGroup);
            });
        }

        timeline.render();
    }

    getEventsAsGroup() {
        let startTime = this.events[0].time;
        let endTime = this.events[this.events.length - 1].time;

        let group = new Group(
            moment(startTime).format('Do MMM YYYY') + ' - ' + moment(endTime).format('Do MMM YYYY'),
            this.events,
            startTime,
            endTime);

        return group;
    }

}
