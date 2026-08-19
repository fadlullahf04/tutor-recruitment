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
        Schema::create('tutor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recruitment_period_id')->nullable()->constrained('recruitment_periods')->onDelete('set null');
            $table->string('registration_number')->unique()->nullable();
            $table->enum('status', ['Draft', 'Pending', 'Approved', 'Rejected'])->default('Draft');
            $table->text('rejection_reason')->nullable();
            $table->integer('completed_step')->default(0); // 0: new/empty, 1: biodata done, 2: education done, 3: documents done/submitted

            // Step 1: Biodata
            $table->string('nip')->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('full_name_with_titles')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('phone')->nullable();
            $table->string('npwp')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();

            // Step 2: Pendidikan
            $table->string('last_education')->nullable(); // S2 / S3 / etc.
            $table->string('title_front')->nullable(); // e.g. Dr., H.
            $table->string('title_back')->nullable(); // e.g. M.Pd., Ph.D.
            $table->string('university_name')->nullable();
            $table->string('graduation_year', 4)->nullable();
            $table->string('field_of_expertise')->nullable();

            // Step 3: Berkas Dokumen (paths)
            $table->string('file_ktp')->nullable();
            $table->string('file_npwp')->nullable();
            $table->string('file_buku_tabungan')->nullable();
            $table->string('file_ijazah')->nullable();
            $table->string('file_cv')->nullable();
            $table->string('file_surat_kesediaan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_profiles');
    }
};
