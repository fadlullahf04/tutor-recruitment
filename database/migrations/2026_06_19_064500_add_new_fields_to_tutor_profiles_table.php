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
        Schema::table('tutor_profiles', function (Blueprint $table) {
            // Step 1: Biodata tambahan
            $table->date('date_of_birth')->nullable()->after('full_name_with_titles');
            $table->text('address')->nullable()->after('date_of_birth');
            $table->string('nidn')->nullable()->after('nip');
            $table->string('nuptk')->nullable()->after('npwp');

            // Step 2: Pendidikan tambahan
            $table->string('field_of_study')->nullable()->after('university_name'); // Bidang Studi

            // Step 3 (NEW): Data Instansi
            $table->string('institution_name')->nullable()->after('field_of_expertise');
            $table->string('employment_status')->nullable()->after('institution_name'); // ASN, Non ASN
            $table->string('work_duration')->nullable()->after('employment_status');
            $table->string('work_field')->nullable()->after('work_duration');
            $table->string('rank_group')->nullable()->after('work_field'); // III, IV, PPPK, Non ASN
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'address',
                'nidn',
                'nuptk',
                'field_of_study',
                'institution_name',
                'employment_status',
                'work_duration',
                'work_field',
                'rank_group'
            ]);
        });
    }
};
