<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

Manager::schema()->create('timelines', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name', 255);
    $table->timestamps();
    $table->softDeletes();
});

Manager::schema()->create('events', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id')->unsigned();
    $table->integer('timeline_id')->unsigned();
    $table->string('title', 255);
    $table->timestamp('time');
    $table->timestamps();
    $table->softDeletes();
    $table->foreign('user_id')->references('id')->on('user');
    $table->foreign('timeline_id')->references('id')->on('timelines');
});

Manager::schema()->create('contents', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('event_id')->unsigned();
    $table->enum('type', ['TEXT', 'IMAGE', 'AUDIO', 'VIDEO', 'URL', 'FILE']);
    $table->text('content');
    $table->timestamps();
    $table->softDeletes();
    $table->foreign('event_id')->references('id')->on('events');
});