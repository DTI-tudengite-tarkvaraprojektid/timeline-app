import BaseGrouper from "./BaseGrouper";
import { Group } from "../timeline/Group";
import { Event } from "../timeline/Event";
const moment = require("moment");
moment.locale('et');

export default class NearGrouper extends BaseGrouper {

    constructor (minDistance) {
        super();
        this.minDistance = minDistance;
    }

    canGroup(lastGroup) {
        let delta = lastGroup.endTime.getTime() - lastGroup.startTime.getTime();
        let count = lastGroup.events.length;
        for (let i = 0; i < count; i++) {
            if (i < count - 1) {
                const event = lastGroup.events[i];
                const nextEvent = lastGroup.events[i + 1];
                const eventsDelta = nextEvent.time.getTime() - event.time.getTime();
                if (eventsDelta / delta < this.minDistance) {
                    return true;
                }
            }
            
        }
        return false;
    }

    getGroups(lastGroup) {
        let delta = lastGroup.endTime.getTime() - lastGroup.startTime.getTime();

        let newGroup = new Group(lastGroup.name, [], lastGroup.startTime, lastGroup.endTime, lastGroup.onClick);
        let newEvents = [];
        let count = lastGroup.events.length;
        for (let i = 0; i < count; i++) {
            const event = lastGroup.events[i];
            let groupable = [];
            let gotGroup = false;
            groupable.push(event.constructor.copyEvent(event));

            for (let j = i + 1; j < count; j++) {
                const nextEvent = lastGroup.events[j];
                const eventsDelta = nextEvent.time.getTime() - event.time.getTime();
                if (eventsDelta / delta < this.minDistance) {
                    gotGroup = true;
                    groupable.push(nextEvent.constructor.copyEvent(nextEvent));
                    i = j;
                    console.log('Found near event');
                } else {
                    break;
                }
            }

            if (gotGroup) {
                let startTime = new Date(event.time.getTime());
                let endTime = new Date(groupable[groupable.length - 1].time.getTime());
                let center = new Date(Math.round((startTime.getTime() + endTime.getTime()) / 2));

                //let delta = endTime.getTime() - startTime.getTime();

                /* for (let i = 0; i < groupable.length; i++) {
                    const groupevent = groupable[i];
                    groupevent.time = new Date(startTime.getTime() + (step / 2) + (step * i));
                } */

                let dayGroup = new Group(
                    '(Mitu sündmust)',
                    groupable,
                    startTime,
                    endTime,
                    null,
                    center
                );
                newEvents.push(dayGroup);
            } else {
                newEvents.push(event);
            }
        }
        newGroup.events = newEvents;
        return newGroup;
    }
}