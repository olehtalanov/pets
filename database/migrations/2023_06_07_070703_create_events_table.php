<?php

use App\Enums\EventRepeatSchemeEnum;
use App\Models\Animal;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', static function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignIdFor(\App\Models\Event::class, 'original_id')
                ->nullable()
                ->constrained((new \App\Models\Event())->getTable())
                ->cascadeOnDelete();
            $table->foreignIdFor(Animal::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('title')->index();
            $table->text('description')->nullable()->fulltext();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->enum('repeat_scheme', array_column(EventRepeatSchemeEnum::cases(), 'value'))
                ->default(EventRepeatSchemeEnum::Never->value);
            $table->boolean('whole_day')->default(0);
            $table->boolean('processable')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
