
import { TimelineManager } from './timeline/TimelineManager.js';
import { TimelineEvent } from './timeline/TimelineEvent.js';

let events = [
    new TimelineEvent('Event 1', new Date('2013-01-01')),
    new TimelineEvent('Event 2', new Date('2013-08-01')),
    new TimelineEvent('Event 3', new Date('2014-02-01')),
    new TimelineEvent('Event 4', new Date('2014-09-01')),
    new TimelineEvent('Event 5', new Date('2015-03-01')),
    new TimelineEvent('Event 6', new Date('2015-10-01')),
    new TimelineEvent('Event 7', new Date('2016-04-01')),
    new TimelineEvent('Event 8', new Date('2016-11-01')),
    new TimelineEvent('Event 9', new Date('2017-05-01')),
    new TimelineEvent('Event 10', new Date('2017-12-01')),
    new TimelineEvent('Event 11', new Date('2018-06-01')),
    new TimelineEvent('Event 12', new Date('2018-01-01')),
    new TimelineEvent('Event 13', new Date('2019-07-01')),
    new TimelineEvent('Event 14', new Date('2019-07-02'))
]

let timelineManager = new TimelineManager('#timeline', '#sub-timeline',events);
timelineManager.render();