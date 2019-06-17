import BaseGrouper from "./BaseGrouper";
import { Group } from "../timeline/Group";
import { Event } from "../timeline/Event";
const moment = require("moment");
moment.locale('et');

export default class SameDayGrouper extends BaseGrouper {

    canGroup(lastGroup) {
        let count = lastGroup.events.length;
        for (let i = 0; i < count; i++) {
            if (i < count - 1) {
                const event = lastGroup.events[i];
                const nextEvent = lastGroup.events[i + 1];
                if (event.time.getTime() == nextEvent.time.getTime()) {
                    console.log("2 events on same date! Can group.");
                    return true;
                }
            }
        }
        return false;
    }

    getGroups(lastGroup) {

        let newGroup = new Group(lastGroup.name, [], lastGroup.startTime, lastGroup.endTime, lastGroup.onClick);
        let newEvents = [];
        let count = lastGroup.events.length;
        for (let i = 0; i < count; i++) {
            const event = lastGroup.events[i];
            let groupable = [];
            let gotGroup = false;
            groupable.push(this.copyEvent(event));

            for (let j = i + 1; j < count; j++) {
                const nextEvent = lastGroup.events[j];
                if (event.time.getTime() == nextEvent.time.getTime()) {
                    gotGroup = true;
                    groupable.push(this.copyEvent(nextEvent));
                    i = j;
                    console.log('Found same day event');
                } else {
                    break;
                }
            }

            if (gotGroup) {
                let startTime = new Date(event.time.getTime());
                let endTime = new Date(event.time.getTime());
                endTime.setHours(23,59,59,999);

                let delta = endTime.getTime() - startTime.getTime();
                let step = delta / groupable.length;

                for (let i = 0; i < groupable.length; i++) {
                    const groupevent = groupable[i];
                    groupevent.time = new Date(startTime.getTime() + (step / 2) + (step * i));
                }

                let dayGroup = new Group(
                    '(Mitu sündmust)',
                    groupable,
                    startTime,
                    endTime
                );
                newEvents.push(dayGroup);
            } else {
                newEvents.push(event);
            }
        }
        newGroup.events = newEvents;
        return newGroup;
    }

    getBaseLog(x, y) {
        return Math.log(y) / Math.log(x);
    }

    copyEvent(event) {
        return new Event(
            event.id,
            event.title,
            new Date(event.time.getTime()),
            event.contentPath,
            event.contentSavePath,
            event.deletePath,
            event.imageUploadPath,
            event.privacy,
            event.fileUploadPath
        );
    }
}