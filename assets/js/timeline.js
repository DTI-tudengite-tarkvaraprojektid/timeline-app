
import { TimelineManager } from './timeline/TimelineManager.js';
import { Event } from './timeline/Event.js';
import { EventManager } from './timeline/EventManager.js';

let timelineManager = new TimelineManager('#timeline', '#sub-timeline', new EventManager('#card-event'))

let timeline = $('#timeline');
if (timeline) {
    // Load events
    $.getJSON(timeline.data('url'), function(data) {
        let events = [];
        data.forEach(event => {
            events.push(new Event(
                event.id,
                event.title,
                new Date(event.time),
                event.path_get_content,
                event.path_save_content,
                event.path_delete,
                event.path_save_image
            ));
        });
        timelineManager.events = events;
        timelineManager.render();
    })
}

$('#new-event-form-button').click(() => {
    $('#new-event-form').submit();
})

$('#edit-event-form-button').click(() => {
    $('#edit-event-form').submit();
})

$('#new-timeline-form-button').click(() => {
    $('#new-timeline-form').submit();
})
