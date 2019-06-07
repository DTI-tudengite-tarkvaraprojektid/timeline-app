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
        this.anchor.empty();

        let count = this.events.length;
        let timelineDelta = this.timelineEnd - this.timelineStart;

        this.anchor.append(this.getTitle(this.group.name));

        for (let i = 0; i < count; i++) {
            let event = this.events[i];
            let delta = event.time.getTime() - this.timelineStart;
            let location = delta / timelineDelta * 100;

            let point = this.getTimelinePoint(i, event.title, null, location);

            point.on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (this.onEventClick != null) {
                    this.onEventClick(event);
                }
            });

            this.anchor.append(point);
        };

        
        this.anchor.append(this.getTitle(this.nextGroup.name, true));
    }

    getTimelinePoint(index, name, description = null, left=0, width=null) {
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

        let header = $('<div></div>');
        header.addClass('point-header');

        point.append(header);
        point.tooltip();
        return point;
    }

    getTitle(title, end = false) {
        let point = $('<div></div>');
        point.addClass('timeline-point')
            .css('left', (end ? 100 : 0) + '%')
        
        let header = $('<div></div>');
        header.addClass('point-header');
        header.text(title)

        point.append(header);
        return point;
    }
}
