
import { TimelineManager } from './timeline/TimelineManager.js';
import { Event } from './timeline/Event.js';
import { EventManager } from './timeline/EventManager.js';

let timelineManager = new TimelineManager('#timeline', '#sub-timeline', new EventManager('#card-event'))

let timeline = 1;

// Load events
$.getJSON('events/' + timeline, function(data) {
    let events = [];
    data.forEach(event => {
        events.push(new Event(event.id, event.title, new Date(event.time)));
    });
    timelineManager.events = events;
    timelineManager.render();
})

$('#new-event-form-button').click(() => {
    $('#new-event-form').submit();
})

