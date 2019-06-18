import { Group } from "./Group";
import { EventManager } from './EventManager';
import tippy from 'tippy.js'

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

    render() {
        let count = this.events.length;
        let timelineDelta = this.timelineEnd - this.timelineStart;
        let timeline = this.initTimeline();

        for (let i = 0; i < count; i++) {
            let event = this.events[i];
            let delta = event.time.getTime() - this.timelineStart;
            let location = delta / timelineDelta * 100;

            let point = this.getTimelinePoint(i, event, location);

            point.on('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (this.onEventClick != null) {
                    this.onEventClick(event, e.target);
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
        container.append($('<small></small>').text(startTitle));
        container.append($('<small></small>').text(endTitle));

        this.anchor.prepend(container);
    }

    getTimelinePoint(index, event, left=0, width=null) {
        let title = '<div class="d-flex mt-1" ><span>' + event.title + '</span>';
        if (event instanceof Group) {
            let startDate = new Date(event.startTime.getFullYear(), event.startTime.getMonth(), event.startTime.getDate());
            let endDate = new Date(event.endTime.getFullYear(), event.endTime.getMonth(), event.endTime.getDate());
            if (startDate.getTime() != endDate.getTime()) {
                title += '<span class="ml-4">' + event.startTime.toLocaleDateString('et') + ' - ' + event.endTime.toLocaleDateString('et') + '</span>';
            } else {
                title += '<span class="ml-4">' + event.time.toLocaleDateString('et') + '</span>';
            }
            
        } else {
            title += '<span class="ml-4">' + event.time.toLocaleDateString('et') + '</span>';
        }
        title += '</div>';
        let point = $('<div></div>');
        point.addClass('timeline-point point-event')
            //.prop('title', title)
            .data('event', index)
            .data('toggle', 'tooltip')
            .data('placement', 'bottom')
            .css('left', left + '%')

        if (event instanceof Group) {
            point.addClass('point-grouped');
            tippy(point[0], {
                interactive: true,
                content: title,
                animateFill: false,
                animation: 'fade',
                flipOnUpdate: true,
                theme: 'light-border',
                arrow: true,
                placement: 'bottom'
            });
        } else {
            title += '<hr>';
            tippy(point[0], {
                interactive: true,
                content: title,
                animateFill: false,
                animation: 'fade',
                flipOnUpdate: true,
                theme: 'light-border',
                arrow: true,
                placement: 'bottom',
                onShow(instance) {
                    fetch(event.contentPath)
                        .then(response => response.json())
                        .then(data => {
                            if (data.content == null || data.content.length == 0) {
                                data = '<p>(Sisu puudub)</p>';
                                instance.setContent(title + data);
                            } else {
                                if (data.content.length > 5) {
                                    data.content = data.content.slice(0, 5);
                                    data.content.push({
                                        insert: '...'
                                    });
                                }
                                let content = EventManager.convertDeltas(data.content);
                                console.log(data);
                                console.log(content);
                                instance.setContent(title + '<div class="text-left">' + content + '</div>');
                            }
                        });
                },
            });
        }

        if (width !== null) {
            point.css('width', width + '%');
        }

        
        return point;
    }
}
