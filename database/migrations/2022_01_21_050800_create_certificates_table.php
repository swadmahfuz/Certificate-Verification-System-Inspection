<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
Since this application shares a common database with the Training Certificate Verification System, this migration file is required in case this application is installed before installing the Training Certificate Verification System application. 

Please note that the Training CVS must be installed in the same domain under a seprate sub-domain. It is recommended to install Training CVS first and then the Inspection CVS.

The .env file for both the applications should be configured in a way so that both Training CVS and Inspection CVS uses the same database and SMTP credentials.

DO NOT DELETE THIS MIGRATION FILE.
 */

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('certificate_number')->unique();
            $table->string('participant_name');
            $table->string('passport_nid');
            $table->string('driving_license')->nullable();
            $table->string('company')->nullable();
            $table->string('training_name');
            $table->string('location');
            $table->string('trainer');
            $table->string('training_date');
            $table->string('training_end');
            $table->string('issue_date');
            $table->string('expiry_date');
            $table->string('created_by')->default('Bulk uploaded');
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes(); ///create 'deleted at' column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificates');
    }
}
