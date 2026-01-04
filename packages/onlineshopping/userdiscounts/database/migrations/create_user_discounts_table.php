<?php


Schema::create('user_discounts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('discount_id')->constrained()->onDelete('cascade');
    $table->unsignedInteger('times_used')->default(0);
    $table->timestamps();

    $table->unique(['user_id', 'discount_id']);
});
