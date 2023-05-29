<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', static function (Blueprint $table) {
            $table->id();
            $table->char('nano_id', 12)->index();
            $table->string('name');
            $table->foreignIdFor(User::class, 'owner_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignIdFor(User::class, 'recipient_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->boolean('is_archived')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
