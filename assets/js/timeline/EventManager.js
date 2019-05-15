export class EventManager {
    constructor(card) {
        this.card = $(card);
        this.visible = false;
    }

    showEvent(event) {
        console.log('showEvent(): ' + event.title);
        this.card.find('.card-header').text(event.title);
        this.card.show();
    }
}