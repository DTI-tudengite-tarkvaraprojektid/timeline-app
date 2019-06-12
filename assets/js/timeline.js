
import { TimelineManager } from './timeline/TimelineManager.js';
import { Event } from './timeline/Event.js';
import { EventManager } from './timeline/EventManager.js';

let timelineManager = new TimelineManager('#timeline', '#sub-timeline', new EventManager('#card-event'))

let timeline = $('#timeline');
if (timeline) {
    // Load events
    $.getJSON(timeline.data('url'), function (data) {
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
});

$('#esearch-form').submit(function (e) {
    e.preventDefault();
    console.log('hello there!');
    var data = $('#esearch').val();
    if (data == '') {

    } else {
        //data = data.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');
        console.log(data);
        var uri = $(this).prop('action') + data;
        $.getJSON(uri, function (data) {
            let events = [];
            data.forEach(event => {
                events.push(new Event(event.id, event.title, new Date(event.time)));
            });
            timelineManager.events = events;
            timelineManager.render();
        })
    }
});

$('#edit-timeline-form-button').click(() => {
    $('#edit-timeline-form').submit();
})
