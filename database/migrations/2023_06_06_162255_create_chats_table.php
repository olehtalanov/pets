<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('chats', static function (Blueprint $table) {
            $userModel = new User();

            $table->id();
            $table->uuid()->unique();
            $table->foreignIdFor(User::class, 'owner_id')
                ->nullable()
                ->constrained($userModel->getTable())
                ->nullOnDelete();
            $table->foreignIdFor(User::class, 'recipient_id')
                ->nullable()
                ->constrained($userModel->getTable())
                ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
