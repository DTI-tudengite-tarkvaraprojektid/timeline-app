require('./timeline.js');

import 'bootstrap';
import '@fancyapps/fancybox';
import '@fortawesome/fontawesome-free/js/fontawesome'
import '@fortawesome/fontawesome-free/js/solid'
import '@fortawesome/fontawesome-free/js/regular'
import '@fortawesome/fontawesome-free/js/brands'
import { userInfo } from 'os';
const moment = require('moment');
require('../scss/app.scss');



$(function () {

    // Show toasts
    $('.toast').toast({ delay: 1500 }).toast('show');

    $('#new-event-date').val(moment().format('YYYY-MM-DD'));

    $('#event-edit-title').click((e) => {
        e.preventDefault();

        $('#edit-event-id').val($('#event-edit-title').data('id'));
        $('#edit-event-name').val($('#event-edit-title').data('title'));
        $('#edit-event-date').val($('#event-edit-title').data('time'));
        $('#edit-event-privacy').prop("checked", $('#event-edit-title').data('private') == 1 ? true : false);
        $('#edit-event-modal').modal('show');
    });

    $('.editBtn').on("click", editTimeline);
    $('.deleteTimelineButton').on("click", deleteTimeline);
    $('#tsearch-form').submit(searchtimelines);
    $('#fsearch-form').submit(searchfiles);

    $('.default-checkbox').click(function (e) {
        var path = $(this).data('path');
        $.post(path, () => {
            console.log('Updated default timeline.');
        });
    })

    $('.edit-user-btn').click(editUser);
    $('.deleteUser').click(deleteUser);

    function deleteTimeline() {
        var timeline = $(this).data("id");
        var name = $(this).data("name");
        if (confirm("Olete kindel, et soovite ajajoont '" + name + "' kustutada?")) {
            console.log("delete funkts");
            window.location.replace("/home/" + timeline + "/delete");
        }

        //window.location.reload();
    }

    function searchfiles() {
        var data = $('#fsearch').val();
        if (data == '') {

        } else {
            console.log(data);
            //data = data.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');
            //console.log(data);
            var uri = $(this).prop('action') + data;
            $(this).prop('action', uri);
        }
    }

    function searchtimelines() {
        var data = $('#tsearch').val();
        if (data == '') {

        } else {
            console.log(data);
            //data = data.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi,'');
            //console.log(data);
            var uri = $(this).prop('action') + data;
            $(this).prop('action', uri);
        }
    }
    $('#new-user-form-button').click(() => {
        $('#new-user-form').submit();
    });

    $('#edit-user-form-button').click(() => {
        $('#edit-user-form').submit();
    });

    function editTimeline(e) {
        e.preventDefault();
        $('#edit-timeline-id').val($(this).data('id'));
        $('#edit-timeline-name').val($(this).data('name'));
        $('#edit-timeline-description').val($(this).data('description'));
        $('#edit-timeline-privacy').prop("checked", $(this).data('private') == 1 ? true : false);
        $('#edit-timeline-modal').modal('show');
    }

    var fname, lname;
    function editUser(e) {
        console.log('click')
        e.preventDefault();
        $('#edit-user-id').val($(this).data('id'));
        $('#edit-user-email').val($(this).data('email'));
        $('#edit-user-firstname').val($(this).data('firstname'));
        $('#edit-user-lastname').val($(this).data('lastname'));
<<<<<<< HEAD
        $('#edit-user-role').prop("checked", $(this).data('admin'));
=======
        $('#user-delete-btn').prop('href', $(this).data('delete-url'));
>>>>>>> d4245c85a53b7ac8232f4a4c342341f102d28d44
        $('#edit-user-modal').modal('show');
        fname = $('#edit-user-firstname').val();
        lname = $('#edit-user-lastname').val();
    }

    function deleteUser(){
        var user = $('#edit-user-id').val();
        // var url = $(this).data('url');
        if (confirm("Kindel, et soovite kasutaja '" + fname + " " + lname + "' kustutada?")){
            window.location.replace("/user/" + user + "/delete");
        }
    }

    $('[data-toggle="tooltip"]').tooltip();
});
