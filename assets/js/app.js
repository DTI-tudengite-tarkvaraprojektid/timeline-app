require('./timeline.js');

import 'bootstrap';
const moment = require('moment');
require('../scss/app.scss');

$(function() {

    // Show toasts
    $('.toast').toast({delay: 1500}).toast('show');

    $('#new-event-date').val(moment().format('YYYY-MM-DD'));

});