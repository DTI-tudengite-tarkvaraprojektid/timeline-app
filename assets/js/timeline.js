
import { TimelineManager } from './timeline/TimelineManager.js';
import { Event } from './timeline/Event.js';
import { EventManager } from './timeline/EventManager.js';

let timelineManager = new TimelineManager('#timeline', '#sub-timeline', new EventManager('#card-event'))

let timeline = $('#timeline');
if (timeline) {
    // Load events
    $.getJSON(timeline.data('url'), function (data) {
        updateTimeline(data);
    })
    $(timeline).fancybox({
        selector : '.event-image'
    });
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
    var data = $('#esearch').val();
    if (!data) {
        var uri = timeline.data('url');
    } else {
        var uri = $(this).prop('action') + '/' + data;
    }
   
    $.getJSON(uri, function (data) {
        updateTimeline(data, true);
    })
});

$('#edit-timeline-form-button').click(() => {
    $('#edit-timeline-form').submit();
})

function updateTimeline(data, searching = false) {
    let events = [];
    data.forEach(event => {
        events.push(new Event(
            event.id,
            event.title,
            new Date(event.time),
            event.path_get_content,
            event.path_save_content,
            event.path_delete,
            event.path_save_image,
            event.private,
            event.path_save_file
        ));
    });
    if (events.length == 0) {
        if (searching) {
            timeline.html(
                '<div class="alert alert-info" role="alert">' +
                    'Ei leidnud ühtegi sündmust :(' +
                '</div>'
            );
        } else {
            timeline.html(
                '<div class="alert alert-info" role="alert">' +
                    'Valitud ajajoonel ei ole ühtegi sündmust :(' +
                '</div>'
            );
        }
    } else {
        timelineManager.events = events;
        timelineManager.render();
    }
}
