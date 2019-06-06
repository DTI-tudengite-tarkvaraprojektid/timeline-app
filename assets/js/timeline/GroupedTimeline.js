export class GroupedTimeline {
    constructor(anchor, groups, onGroupClick = null) {
        this.anchor = $(anchor);
        this.groups = groups;
        this.onGroupClick = onGroupClick;
    }

    render () {
        this.anchor.empty();

        let count = this.groups.length;
        let groupWidth = 100 / (count - 1);

        for (let i = 0; i < count; i++) {
            let group = this.groups[i];
            let groupDelta = group.endTime.getTime() - group.startTime.getTime();

            let point = this.getGroupTimelinePoint(i, group.name, null, groupWidth * i, (i == count - 1 ? 0 : groupWidth));

            point.on('click', () => {
                if (this.onGroupClick != null) {
                    this.onGroupClick(group);
                }
            });

            this.anchor.append(point);
            
            // Add small dots in between
            group.events.forEach(event => {
                let delta = event.time.getTime() - group.startTime.getTime();
                let location = (groupWidth * i) + delta / groupDelta * groupWidth;
                this.anchor.append(this.getSmallTimelinePoint(location, event.title));
            });
        }
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
