<?php

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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->string('job_title');
            $table->date('application_date');
            $table->string('job_posting_url')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('desired_salary', 10, 2)->nullable();
            $table->enum('application_type', ['initiativ', 'ausschreibung']);
            $table->enum('source', ['linkedin', 'firmenwebsite', 'karriereportal', 'empfehlung', 'sonstiges']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
