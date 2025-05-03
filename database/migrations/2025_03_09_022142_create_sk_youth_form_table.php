<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('sk_youth_form', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->date('dob');
            $table->string('gender');
            $table->longText('national_id');
            $table->text('address');
            $table->string('phone');
            $table->string('email');
            $table->string('education');
            $table->string('school_name')->nullable();
            $table->string('voter_status');
            $table->longText('voter_id')->nullable();
            $table->string('youth_org');
            $table->text('skills')->nullable();
            $table->string('volunteer');
            $table->string('guardian_name');
            $table->string('guardian_contact');
            $table->longText('profile_picture')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('sk_youth_form');
    }
};

