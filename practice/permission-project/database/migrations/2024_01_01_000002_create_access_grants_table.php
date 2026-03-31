<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Stores which higher-role user has granted access to which lower-role user
        Schema::create('access_grants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('granter_id')->constrained('users')->onDelete('cascade'); // who gave access
            $table->foreignId('grantee_id')->constrained('users')->onDelete('cascade'); // who received access
            $table->enum('access_level', ['read', 'write', 'full'])->default('read');
            $table->json('permissions')->nullable(); // specific permissions array
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['granter_id', 'grantee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_grants');
    }
};
