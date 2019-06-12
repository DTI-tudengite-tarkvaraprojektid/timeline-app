import Quill from "../quill";
import { QuillDeltaToHtmlConverter } from 'quill-delta-to-html'; 
const moment = require("moment");

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
        if (this.editing) {
            $('#event-editor-container').addClass('card-body');
            $('#event-editor-container').html(
                $('<div></div>').prop('id', 'event-editor').addClass('ql-editor').html(this.quill.root.innerHTML)
            );
            this.quill = null;
            this.editing = false;
        }
        console.log('showEvent(): ' + event.title);
        this.card.find('#event-title').text(event.title);
        this.card.find('#event-time').text(event.time.toLocaleDateString('et') + (event.private ? ' - (Privaatne)' : ''));
        this.card.find('#event-edit-title').data('id', event.id);
        this.card.find('#event-edit-title').data('title', event.title);
        this.card.find('#event-edit-title').data('time', moment(event.time).format('YYYY-MM-DD'));
        this.card.find('#event-edit-title').data('private', event.private);
        this.card.find('#event-editor').text('Loading...');
        this.card.show();
        this.currentEvent = event;
        this.loadContent();
    }

    deleteEvent(event) {
        console.log("delete funkts");
        window.location.replace("/events/"+event+"/delete");
        //window.location.reload();
    }

    convertDeltas(ops) {
        var cfg = {};
        var converter = new QuillDeltaToHtmlConverter(ops, cfg);

        converter.afterRender(function(groupType, htmlString){
            var elements = $.parseHTML(htmlString)
            $(elements).find('img').each((index, node) => {
                console.log("Changing image");
                $(node).addClass('img-fluid')
            })
            return $(elements).html();
        });

        return converter.convert();
    }

    loadContent() {
        $.getJSON(this.currentEvent.contentPath, (data) => {
            this.card.find('#event-editor').html(this.convertDeltas(data.content));
        });
    }

    saveContent() {
        $.ajax({
            url: this.currentEvent.contentSavePath,
            data : JSON.stringify(this.quill.getContents().ops),
            contentType : 'application/json',
            type: 'POST'
        }).done((data) => {
            $('#event-editor-container').addClass('card-body');
            $('#event-editor-container').html(
                $('<div></div>').prop('id', 'event-editor').addClass('ql-editor').html(this.quill.root.innerHTML)
            );
            this.quill = null;
            this.editing = false;
        });
    }

    toggleEdit() {
        if (this.editing) {
            console.log(this.quill.getContents().ops);
            this.saveContent();
        } else {
            $('#event-editor-container').removeClass('card-body');
            var toolbarOptions = [[{'size': []}, 'bold', 'italic', 'underline', 'strike'], [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }], ['link', 'image', 'video']];
            this.quill = new Quill('#event-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions,
                    imageUpload: {
                        url: this.currentEvent.imageUploadPath,
                        name: 'image', // custom form name
                        // personalize successful callback and call next function to insert new url to the editor
                        callbackOK: (serverResponse, next) => {
                            next(serverResponse.path);
                        },
                        // personalize failed callback
                        callbackKO: serverError => {
                            alert(serverError);
                        },
                        // optional
                        // add callback when a image have been chosen
                        checkBeforeSend: (file, next) => {
                            console.log(file);
                            next(file); // go back to component and send to the server
                        }
                    }
                }
            });
            this.editing = true;
        }
    }
}