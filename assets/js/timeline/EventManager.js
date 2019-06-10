import Quill from "../quill";

export class EventManager {
    constructor(card) {
        this.card = $(card);
        this.visible = false;
        this.editing = false;
        this.quill = null;
        this.currentEvent = null;

        $('#event-edit').on('click', (e) => {
            e.preventDefault();
            this.toggleEdit();
        });

        $('#deleteEventButton').on("click", () => {
            
            if(confirm(this.currentEvent.title)){
                event = this.currentEvent.id;
                this.deleteEvent(event);
            };
        });
    }
    
    showEvent(event) {
        console.log('showEvent(): ' + event.title);
        this.card.find('#event-title').text(event.title);
        this.card.find('#event-time').text(event.time.toLocaleDateString('et'));
        this.card.show();
        this.currentEvent = event;
    }

    deleteEvent(event) {
        console.log("delete funkts");
        window.location.replace("/events/"+event+"/delete");
        //window.location.reload();
    }

    toggleEdit() {
        
        if (this.editing) {
            console.log(this.quill.getContents());
            $('#event-editor-container').addClass('card-body');
            $('#event-editor-container').html(
                $('<div></div>').prop('id', 'event-editor').html(this.quill.root.innerHTML)
            );
            this.quill = null;
        } else {
            $('#event-editor-container').removeClass('card-body');
            var toolbarOptions = [[{'size': []}, 'bold', 'italic', 'underline', 'strike'], [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }], ['link', 'image', 'video']];
            this.quill = new Quill('#event-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                }
            });
        }
        this.editing = !this.editing;
    }
}