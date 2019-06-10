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
        
        $('#edit-event-name').val($('#event-edit-title').data('title'));
        $('#edit-event-date').val($('#event-edit-title').data('time'));
        $('#edit-event-id').val($('#event-edit-title').data('id'));
        $('#edit-event-modal').modal('show');   
    });
});