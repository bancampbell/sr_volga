<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            // Основные поля
            $table->string('name');
            $table->string('handle')->unique(); // main, footer, sidebar
            $table->string('description')->nullable();

            // Nested set для дерева (нужен пакет kalnoy/nestedset)
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('_lft')->default(0);
            $table->integer('_rgt')->default(0);

            // Тип и ссылка
            $table->string('type'); // link, page, category, material, custom, external
            $table->string('url')->nullable();
            $table->string('route_name')->nullable();
            $table->json('route_params')->nullable();

            // Внешние ссылки
            $table->string('external_url')->nullable();
            $table->string('target')->default('_self');

            // Медиа
            $table->string('icon')->nullable();
            $table->string('image')->nullable();

            // Состояние
            $table->boolean('is_active')->default(true);
            $table->boolean('is_new_tab')->default(false);
            $table->integer('sort')->default(0);

            // Полиморфные связи
            $table->nullableMorphs('linkable'); // для связи с категориями/материалами

            // Permissions
            $table->json('roles')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Индексы
            $table->index('handle');
            $table->index('type');
            $table->index('is_active');
            $table->index(['parent_id', '_lft', '_rgt']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
