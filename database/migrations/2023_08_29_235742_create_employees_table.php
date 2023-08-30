<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Department;
use App\Models\State;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Country::class)->references('id')->on('countries')->cascadeOnDelete();
            $table->foreignIdFor(State::class)->references('id')->on('states')->cascadeOnDelete();
            $table->foreignIdFor(City::class)->references('id')->on('cities')->cascadeOnDelete();
            $table->foreignIdFor(Department::class)->references('id')->on('departments')->cascadeOnDelete();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('address');
            $table->char('zip_code');
            $table->date('birth_date');
            $table->date('date_hired');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
