<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sent_notifications', function (Blueprint $table) {
            $table->string('recipient_phone', 20)->nullable();
            $table->unsignedInteger('recipient_count')->default(0);
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('sent_notifications', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_phone',
                'recipient_count',
                'sent_count',
                'failed_count',
            ]);
        });
    }
};
