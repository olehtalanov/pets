<?php

use App\Enums\Animal\SexEnum;
use App\Enums\Animal\WeightUnitEnum;
use App\Models\AnimalType;
use App\Models\Breed;
use App\Models\User;
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
        Schema::create('animals', static function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->enum('sex', array_column(SexEnum::cases(), 'value'));
            $table->date('birth_date');

            $table->foreignIdFor(AnimalType::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('custom_type_name')->nullable();

            $table->foreignIdFor(Breed::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('custom_breed_name')->nullable();
            $table->string('breed_name')->nullable();
            $table->boolean('metis')->default(0);

            $table->double('weight');
            $table->enum('weight_unit', array_column(WeightUnitEnum::cases(), 'value'));

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
