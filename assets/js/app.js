require('./timeline.js');

import 'bootstrap';
const moment = require('moment');
require('../scss/app.scss');

$(function() {

    // Show toasts
    $('.toast').toast({delay: 1500}).toast('show');

    $('#new-event-date').val(moment().format('YYYY-MM-DD'));

    $('#event-edit-title').click((e) => {
        e.preventDefault();

        $('#edit-event-id').val($('#event-edit-title').data('id'));
        $('#edit-event-name').val($('#event-edit-title').data('title'));
        $('#edit-event-date').val($('#event-edit-title').data('time'));
        $('#edit-event-privacy').prop( "checked", $('#event-edit-title').data('private') == 1 ? true : false);
        $('#edit-event-modal').modal('show');        
      });
      $('.deleteTimelineButton').on("click", deleteTimeline);
    $('#tsearch-form').submit(searchtimelines);
    $('.editBtn').click((e) => {
        e.preventDefault();
        $('#edit-timeline-id').val($('.editBtn').data('id'));
        $('#edit-timeline-name').val($('.editBtn').data('name'));
        $('#edit-timeline-description').val($('.editBtn').data('description'));
        $('#edit-timeline-privacy').prop( "checked", $('.editBtn').data('private') == 1 ? true : false);
        $('#edit-timeline-modal').modal('show');
    });

    function deleteTimeline(e) {
      var timeline = $(this).data("id");
      if(confirm(timeline)){
          console.log("delete funkts");
          window.location.replace("/timelines/"+timeline+"/delete");
      }

        //window.location.reload();
    }

    function searchtimelines(){
      var data = $('#tsearch').val();
      if(data == ''){

      } else {
        console.log(data);
        //data = data.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');
        //console.log(data);
        var uri = $(this).prop('action') + data;
        $(this).prop('action', uri);
      }
    }




  });
