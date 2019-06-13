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
        this.content = null;

        $('#event-edit').on('click', (e) => {
            e.preventDefault();
            this.toggleEdit();
        });

        $('#deleteEventButton').on("click", () => {
            if(confirm("Olete kindel, et soovite sündmust "+ "'"+this.currentEvent.title+"'"+ " kustutada?")){
                event = this.currentEvent.id;
                this.deleteEvent(event);
            };
        });
    }
    showEvent(event, element) {
        if (this.editing) {
            this.endEditing();
        }
        console.log('showEvent(): ' + event.title);
        $("div.timeline div").removeClass("active");
        element.classList.add("active");
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

        converter.renderCustomWith(function(customOp, contextOp){
            if (customOp.insert.type === 'thumbnailImage') {
                let val = customOp.insert.value;
                console.log('adding thumbnail:')
                console.log(val)
                var link = $('<a></a>')
                    .addClass('event-image')
                    .prop('href', val.path)
                    .data('fancybox', 'images')
                    .append(
                        $('<img></img>')
                            .addClass('img-fluid')
                            .prop('src', val.thumbnail)
                    );
                return link[0].outerHTML;
            } else {
                return 'Unmanaged custom blot!';
            }
        });

        converter.afterRender(function(groupType, htmlString){
            var wrapper = document.createElement('div');
            wrapper.innerHTML= htmlString;
            var elements = $(wrapper);
            $(elements).find('img:not(.img-fluid)').each((index, node) => {
                console.log(node);
                var src = $(node).prop('src');
                var link = $('<a></a>')
                    .addClass('event-image')
                    .prop('href', src)
                    .data('fancybox', 'images')
                    .append(
                        $('<img></img>')
                            .addClass('img-fluid')
                            .prop('src', src)
                    );
                $(node).replaceWith(link);
            })
            return $(elements).html();
        });

        return converter.convert();
    }

    loadContent() {
        $.getJSON(this.currentEvent.contentPath, (data) => {
            this.content = data.content;
            this.card.find('#event-editor').html(this.convertDeltas(this.content));
        });
    }

    saveContent() {
        $.ajax({
            url: this.currentEvent.contentSavePath,
            data : JSON.stringify(this.quill.getContents().ops),
            contentType : 'application/json',
            type: 'POST'
        }).done((data) => {
            this.endEditing();
        });
    }

    endEditing() {
        $('#event-edit').removeClass('list-group-item-success').text('Muuda sisu');
        $('#event-editor-container').addClass('card-body');
        $('#event-editor-container').html(
            $('<div></div>').prop('id', 'event-editor').addClass('ql-editor').html(this.quill.root.innerHTML)
        );
        this.quill = null;
        this.editing = false;
    }

    toggleEdit() {
        if (this.editing) {
            console.log(this.quill.getContents().ops);
            this.saveContent();
            this.loadContent();
        } else {
            $('#event-edit').addClass('list-group-item-success').text('Salvesta sisu');
            $('#event-editor-container').removeClass('card-body');
            var toolbarOptions = [[{'size': []}, 'bold', 'italic', 'underline', 'strike'], [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }], ['link', 'image', 'video']];
            $('#event-editor').html('');
            this.quill = new Quill('#event-editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions,
                    imageUpload: {
                        url: this.currentEvent.imageUploadPath,
                        images: this.currentEvent.imageListPath,
                        name: 'image', // custom form name
                        // personalize successful callback and call next function to insert new url to the editor
                        callbackOK: (serverResponse, next) => {
                            next({
                                path: serverResponse['path'],
                                thumbnail: serverResponse['thumbnail-path']
                            });
                        },
                        // personalize failed callback
                        callbackKO: serverError => {
                            alert(JSON.parse(serverError.body).message);
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
            this.quill.setContents(this.content);
            this.editing = true;
        }
    }
}
