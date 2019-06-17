export class GroupedTimeline {
    constructor(anchor, groups, onGroupClick = null) {
        this.anchor = $(anchor);
        this.groups = groups;
        this.onGroupClick = onGroupClick;
    }

    render () {
        
        let count = this.groups.length;
        let groupWidth = 100 / (count);
        let timeline = this.initTimeline();

        for (let i = 0; i < count; i++) {
            let group = this.groups[i];
            let groupDelta = group.endTime.getTime() - group.startTime.getTime();

            let point = this.getGroupTimelinePoint(i, group.name, null, groupWidth * i, groupWidth);

            timeline.append(point);

           //if (i < count - 1) {
                point.on('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    if (this.onGroupClick != null) {
                        this.onGroupClick(group, this.groups[i + 1]);
                    }
                });

                // Add small dots in between
                group.events.forEach(event => {
                    let delta = event.time.getTime() - group.startTime.getTime();
                    let location = (groupWidth * i) + delta / groupDelta * groupWidth;
                    timeline.append(this.getSmallTimelinePoint(location, event.title));
                });
            //}

        }
        // Add last point
        let point = this.getGroupTimelinePoint(-1, '', null, 100);
        timeline.append(point);
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

    getGroupTimelinePoint(index, name, description = null, left=0, width=0) {
        let event = $('<div></div>');
        event.addClass('timeline-point point-year')
            .data('group', index)
            .css('left', left + '%');
            
        if (width > 0) {
            event.css('width', width + '%');
        }

        let point = $('<div></div>');
        point.addClass('point-header');
        event.append(point);

        let header = $('<p></p>');
        header.text(name);
        point.append(header);

        if (description != null) {
            let subHeader = $('<small></small>');
            subHeader.text(description);
            point.append(subHeader);
        }

        return event;
    }

    getSmallTimelinePoint(left=0, name='') {
        let event = $('<div></div>');
        event.addClass('timeline-point point-small')
            .data('name', name)
            .css('left', left + '%');

        return event;
    }
}
