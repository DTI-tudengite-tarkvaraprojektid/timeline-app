export class Timeline {
    constructor(anchor, group, nextGroup, onEventClick = null) {
        this.anchor = $(anchor);
        this.events = group.events;
        this.group = group;
        this.nextGroup = nextGroup;
        this.startTime = group.startTime;
        this.endTime = group.endTime;
        this.timelineStart = this.startTime.getTime();
        this.timelineEnd = this.endTime.getTime();
        this.onEventClick = onEventClick;
    }

    render () {
        let count = this.events.length;
        let timelineDelta = this.timelineEnd - this.timelineStart;
        let timeline = this.initTimeline();

        for (let i = 0; i < count; i++) {
            let event = this.events[i];
            let delta = event.time.getTime() - this.timelineStart;
            let location = delta / timelineDelta * 100;

            let point = this.getTimelinePoint(i, event.title, location);

            point.on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (this.onEventClick != null) {
                    this.onEventClick(event);
                }
            });

            timeline.append(point);
        };

        this.setTitles(this.group.name, this.nextGroup.name);
    }

    initTimeline() {
        this.anchor.empty();

        let container = $('<div></div>');
        container.addClass("timeline-container");

        let timeline = $('<div></div>');
        timeline.addClass("timeline");

        container.append(timeline);
        this.anchor.append(container);
        return timeline;
    }

    setTitles(startTitle, endTitle) {
        let container = $('<div></div>');
        container.addClass('d-flex justify-content-between');
        container.append($('<span></span>').text(startTitle));
        container.append($('<span></span>').text(endTitle));
        
        this.anchor.prepend(container);
    }

    getTimelinePoint(index, name, left=0, width=null) {
        let point = $('<div></div>');
        point.addClass('timeline-point point-event')
            .prop('title', name)
            .data('event', index)
            .data('toggle', 'tooltip')
            .data('placement', 'bottom')
            .css('left', left + '%')

        if (width !== null) {
            point.css('width', width + '%');
        }

        point.tooltip();
        return point;
    }


}
