<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // We're removing this migration as we'll use order_items table instead
        // No action needed
    }

    public function down(): void
    {
        // No action needed
    }
};
