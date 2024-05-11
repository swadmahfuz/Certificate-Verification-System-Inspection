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

class AddTrainingEndToCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('training_end')->nullable()->after('training_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('training_end');
        });
    }
}
