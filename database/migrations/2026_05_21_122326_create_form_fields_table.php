<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('form_fields', function (Blueprint $table) {
        $table->id();
        $table->foreignId('form_id')->constrained()->onDelete('cascade'); // Form delete toh fields bhi delete
        $table->string('type'); // text, email, number, select, file, textarea
        $table->string('label'); // e.g., "Full Name"
        $table->string('name'); // e.g., "full_name" (backend request key)
        $table->boolean('is_required')->default(false);
        $table->json('validation_rules')->nullable(); // Regex ya min/max criteria ke liye
        $table->json('options')->nullable(); // Dropdown ya Checkbox ke options store karne ke liye
        $table->integer('order_index')->default(0); // Drag-drop layout sorting order
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};
