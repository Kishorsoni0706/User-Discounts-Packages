<?php


Schema::create('discount_audits', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('discount_id')->constrained()->onDelete('cascade');
    $table->string('action'); // assigned, revoked, applied
    $table->json('metadata')->nullable();
    $table->timestamps();
});
