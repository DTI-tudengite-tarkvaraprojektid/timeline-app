<?php

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

Manager::schema()->create('timelines', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id')->unsigned();
    $table->string('name', 255);
    $table->text('description');
    $table->timestamps();
    $table->softDeletes();
    $table->foreign('user_id')->references('id')->on('user');
    $table->boolean('private');
    $table->boolean('default');
});

// Full Text Index
Manager::statement('ALTER TABLE timelines ADD FULLTEXT fulltext_index (name, description)');

Manager::schema()->create('events', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id')->unsigned();
    $table->integer('timeline_id')->unsigned();
    $table->string('title', 255);
    $table->text('content');
    $table->dateTime('time')->useCurrent();
    $table->timestamps();
    $table->softDeletes();
    $table->foreign('user_id')->references('id')->on('user');
    $table->foreign('timeline_id')->references('id')->on('timelines');
    $table->boolean('private');
});

// Full Text Index
Manager::statement('ALTER TABLE events ADD FULLTEXT fulltext_index (title, content)');

Manager::schema()->create('contents', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('event_id')->unsigned();
    $table->enum('type', ['TEXT', 'IMAGE', 'AUDIO', 'VIDEO', 'URL', 'FILE']);
    $table->text('content');
    $table->timestamps();
    $table->foreign('event_id')->references('id')->on('events');
});

Manager::statement('ALTER TABLE contents ADD FULLTEXT fulltext_index (content)');
