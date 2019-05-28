
import { TimelineManager } from './timeline/TimelineManager.js';
import { Event } from './timeline/Event.js';
import { EventManager } from './timeline/EventManager.js';

let timelineManager = new TimelineManager('#timeline', '#sub-timeline', new EventManager('#card-event'))
/*  = [
    new Event(0, 'Event 1', new Date('2013-01-01')),
    new Event(1, 'Event 2', new Date('2013-08-01')),
    new Event(2, 'Event 3', new Date('2014-02-01')),
    new Event(3, 'Event 4', new Date('2014-09-01')),
    new Event(4, 'Event 5', new Date('2015-03-01')),
    new Event(5, 'Event 6', new Date('2015-10-01')),
    new Event(6, 'Event 7', new Date('2016-04-01')),
    new Event(7, 'Event 8', new Date('2016-11-01')),
    new Event(8, 'Event 9', new Date('2017-05-01')),
    new Event(9, 'Event 10', new Date('2017-12-01')),
    new Event(10, 'Event 11', new Date('2018-06-01')),
    new Event(11, 'Event 12', new Date('2018-01-01')),
    new Event(12, 'Event 13', new Date('2019-07-01')),
    new Event(13, 'Event 14', new Date('2019-07-02'))
]; */

$.getJSON('/events/', function(data) {
    let events = [];
    data.forEach(event => {
        events.push(new Event(event.id, event.title, new Date(event.time)));
    });
    timelineManager.events = events;
    timelineManager.render();
})

