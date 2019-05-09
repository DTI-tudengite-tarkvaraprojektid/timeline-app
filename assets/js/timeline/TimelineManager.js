export class TimelineManager {
    constructor(timeline, subTimeline, events = []) {
        this.timeline = $(timeline);
        this.subTimeline = $(subTimeline);
        this.zoom = 0;
        this.events = events;

        //this.subTimeline.hide();

        this.initEvents();
    }


    initEvents() {
        this.timeline.on('click', '.point-year', (e) => {
            e.preventDefault();
            let point = $(e.target);
            this.renderSubTimeline(point.data('year'));
        });
    }

    render() {
        this.timeline.empty();
        if (this.zoom == 0) {
            let startYear = this.events[0].time.getFullYear();
            let endYear = this.events[this.events.length - 1].time.getFullYear();
            let totalYears = endYear - startYear + 2 // + 2 because we want to include both, start and end year, as well
            
            let timelineStart = new Date(startYear, 0, 1).getTime();
            let timelineEnd = new Date(endYear + 1, 0, 1).getTime();
            let timelineDelta = timelineEnd - timelineStart;
            
            for (let i = 0; i < totalYears; i++) {
                this.timeline.append(this.getYearTimelinePoint(startYear + i, null, 100 / (totalYears - 1) * i, 100 / (totalYears- 1)));
            }

            this.events.forEach(event => {
                let delta = event.time.getTime() - timelineStart;
                let location = delta / timelineDelta * 100
                this.timeline.append(this.getEmptyTimelinePoint(location, event.title));
            });
        }
    }

    renderSubTimeline(year) {
        this.subTimeline.parent().collapse('show');
        this.subTimeline.empty();

        let timelineStart = new Date(year, 0, 1).getTime();
        let timelineEnd = new Date(year + 1, 0, 1).getTime();
        let timelineDelta = timelineEnd - timelineStart;

        this.events.forEach(event => {
            let delta = event.time.getTime() - timelineStart;
            if (delta >= 0 && delta <= timelineDelta) {
                let location = delta / timelineDelta * 100
                this.subTimeline.append(this.getTimelinePoint(event.title, event.time.toLocaleString(), location));
            }
        });

    }

    getTimelinePoint(title, description = null, left=0, width=null) {
        return '<div class="timeline-point point-year" style="left: ' + left + '%' + (width !== null ? '; width: ' + width + '%' : '') + '">' +
            '<div class="point-header">' +
                '<p>' + title + '</p>' +
                (description !== null ? '<small>' +  description+ '</small>' : '') +
            '</div>' +
        '</div>';
    }

    getYearTimelinePoint(year, description = null, left=0, width=0) {
        return '<div class="timeline-point point-year" data-year="' + year + '" style="left: ' + left + '%; width: ' + width + '%">' +
            '<div class="point-header">' +
                '<p>' + year + '</p>' +
                (description != null ? '<small>' +  description+ '</small>' : '') +
            '</div>' +
        '</div>';
    }

    getEmptyTimelinePoint(left=0, name='') {
        return '<div class="timeline-point point-small" data-name="' + name + '" style="left: ' + left + '%">' +
            '</div>';
    }


}
