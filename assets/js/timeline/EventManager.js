import { QuillDeltaToHtmlConverter } from 'quill-delta-to-html';
import getQuill from '../quill';
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
            if(confirm("Olete kindel, et soovite sündmust '" + this.currentEvent.title + "' kustutada?")){
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
        window.location.hash = 'event-' + event.id;
    }

    deleteEvent(event) {
        window.location.replace(this.currentEvent.deletePath);
    }

    convertDeltas(ops) {
        var cfg = {};
        var converter = new QuillDeltaToHtmlConverter(ops, cfg);

        converter.renderCustomWith(function(customOp, contextOp){
            console.log(customOp.insert.type);
            if (customOp.insert.type === 'thumbnailImage') {
                let val = customOp.insert.value;
                var link = $('<a></a>')
                    .addClass('event-image')
                    .prop('href', val.path)
                    .data('fancybox', 'images')
                    .append(
                        $('<img></img>')
                            .addClass('img-thumbnail m-1 event-thumbnail')
                            .prop('src', val.thumbnail)
                    );
                return link[0].outerHTML;
            } else if (customOp.insert.type === 'customImage') {
                let val = customOp.insert.value;
                var link = $('<a></a>')
                    .addClass('event-image')
                    .prop('href', val.path)
                    .data('fancybox', 'images')
                    .append(
                        $('<img></img>')
                            .addClass('img-fluid')
                            .prop('src', val.path)
                    );
                return link[0].outerHTML;
            } else if (customOp.insert.type === 'file') {
                let val = customOp.insert.value;
                console.log(val);
                var link = $('<span></span>')
                    .addClass('event-file border p-1')
                    .append(
                        $('<i></i>').addClass('fas fa-download mr-2')
                    )
                    .append(
                        $('<a></a>')
                            .prop('href', val.path)
                            .prop('download', val.name)
                            .text(val.name)
                    );
                return link[0].outerHTML;
            } else {
                return 'Unmanaged custom blot!';
            }
        });

        converter.beforeRender(function(groupType, data){
            if (groupType == 'block') {
               /*  data.ops.forEach(op => {
                    console.log(op)
                    if (op.attributes.file != undefined) {
                        var file = op.attributes.file;
                        var element = $('<a></a>')
                            .addClass('event-file')
                            .prop('href', file.path)
                            .prop('download', file.name)
                            .data('id', file.id)
                            .text(op.insert.value);
                        return element[0].outerHTML;
                    }
                }); */
                
            }
        });

        /* converter.afterRender(function(groupType, htmlString){
            var wrapper = document.createElement('div');
            wrapper.innerHTML= htmlString;
            var elements = $(wrapper);
            $(elements).find('img:not(.img-thumbnail)').each((index, node) => {
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
        }); */

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
            $('#event-editor').html('');
            this.quill = getQuill('#event-editor', this.currentEvent);
            this.quill.setContents(this.content);
            $('.ql-file').addClass('').html('<i class="far fa-file text-center"></i>');
            
            this.editing = true;
        }
    }
}
