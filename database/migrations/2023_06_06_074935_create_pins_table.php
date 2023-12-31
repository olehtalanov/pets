<?php

use App\Models\PinType;
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
        Schema::create('pins', static function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(PinType::class, 'type_id')
                ->nullable()
                ->constrained((new PinType())->getTable())
                ->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->text('contact')->nullable();
            $table->double('latitude', 10, 8);
            $table->double('longitude', 11, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pins');
    }
};
