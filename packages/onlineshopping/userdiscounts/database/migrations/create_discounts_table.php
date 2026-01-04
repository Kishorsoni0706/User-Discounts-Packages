<?php


Schema::create('discounts', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->unsignedTinyInteger('percentage'); // e.g. 10 = 10%
    $table->boolean('active')->default(true);
    $table->timestamp('expires_at')->nullable();
    $table->unsignedInteger('usage_limit_per_user')->nullable();
    $table->timestamps();
});
