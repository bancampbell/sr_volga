<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('handle')->unique();
            $table->string('description')->nullable();
            $table->integer('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->foreignId('menu_category_id')
                ->nullable()
                ->after('id')
                ->constrained('menu_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['menu_category_id']);
            $table->dropColumn('menu_category_id');
        });

        Schema::dropIfExists('menu_categories');
    }
};
