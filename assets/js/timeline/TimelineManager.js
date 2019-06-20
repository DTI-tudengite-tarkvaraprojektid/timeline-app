import { Group } from "./Group";
import { Timeline } from "./Timeline";
import { GroupedTimeline } from "./GroupedTimeline";
import GroupManager from "../grouping/GroupManager";
import CountRule from "../grouping/CountRule";
import YearGrouper from "../grouping/YearGrouper";
import MonthGrouper from "../grouping/MonthGrouper";
import SameDayRule from "../grouping/SameDayRule";
import SameDayGrouper from "../grouping/SameDayGrouper";
import NearRule from "../grouping/NearRule";
import NearGrouper from "../grouping/NearGrouper";
const moment = require("moment");
moment.locale('et');

export class TimelineManager {
    constructor(timeline, subTimeline, eventManager, events = []) {
        this.timelineSelector = timeline;
        this.timeline = $(timeline);
        this.subTimelineSelector = subTimeline;
        this.subTimeline = $(subTimeline);
        this.eventsGroup = null;
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

    setEvents(events) {
        this.events = events;
        if (this.eventsGroup == null) {
            this.eventsGroup = this.getEventsAsGroup();
        } else {
            this.eventsGroup.events = events;
        }
    }

    initEvents() {
        /*this.subTimeline.on('click', (e) => {
            e.preventDefault();
            $('#new-event-modal').modal('show');
        });*/
    }

    render() {
        this.renderTimeline(this.eventsGroup);
    }

    renderTimeline(group, depth = 0, nextGroup = null, skipGrouper = false) {
        console.log('Depth: ' + depth);
        //this.timeline.empty();

        var groupManager = new GroupManager([
            new CountRule(10, [
                // new SameDayGrouper(),
                // new NearGrouper(0.025),
                new YearGrouper(5),
                new MonthGrouper(5)
            ]),
            new NearRule(0.025, [
                new SameDayGrouper(),
                new NearGrouper(0.025)
            ]),
            new SameDayRule(1, [
                new SameDayGrouper()
            ])
        ]);

        if (skipGrouper) {
            var groups = false;
        } else {
            var groups = groupManager.group(group);

        }

        var selector;
        if (depth > 0) {
            selector = this.subTimelineSelector;
            this.subTimeline.collapse('show');
        } else {
            selector = this.timelineSelector;
            this.subTimeline.collapse('hide');
        }

        if (groups instanceof Group) {
            group = groups;
            groups = false;
            nextGroup = null;
        }
        if (groups === false) {
            groups = groupManager.getSimpleGroups(group);
            group = groups[0];
            nextGroup = groups[1];

            var timeline = new Timeline(selector, group, nextGroup, (event, element) => {
                if (depth == 0) {
                    $(".active-group").removeClass('active-group');
                }
                if (event instanceof Group) {
                    element.classList.add('active-group');
                    this.renderTimeline(event, depth + 1, null, false);
                } else {
                    if (depth == 0) {
                        this.subTimeline.collapse('hide');
                    }
                    this.eventManager.showEvent(event, element);
                }
            });
        } else {
            var timeline = new GroupedTimeline(selector, groups, (group, nextGroup, element) => {
                $(".active-group").removeClass("active-group");
                $(element).addClass("active-group");
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
