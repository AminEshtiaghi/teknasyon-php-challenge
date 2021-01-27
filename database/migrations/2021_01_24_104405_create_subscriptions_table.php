<?php

use App\Models\Subscription;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Subscription::TABLE, function (Blueprint $table) {
            $table->id();
            $table->uuid(Subscription::COLUMN_CLIENT_TOKEN);
            $table->string(Subscription::COLUMN_RECEIPT, 255);
            $table->enum(Subscription::COLUMN_STATUS, Subscription::STATUES);
            $table->timestamps();

            $uniqueClientTokenIndex = 'u_' . Subscription::TABLE . '_' . Subscription::COLUMN_CLIENT_TOKEN;
            $table->unique(
                [
                    Subscription::COLUMN_CLIENT_TOKEN
                ],
                $uniqueClientTokenIndex
            );

            $clientTokenStatusIndex = 'i_' . Subscription::TABLE . '_' . Subscription::COLUMN_STATUS . '_' . Subscription::COLUMN_EXPIRE_AT;
            $table->index(
                [
                    Subscription::COLUMN_STATUS,
                    Subscription::COLUMN_EXPIRE_AT,
                    Subscription::COLUMN_ID
                ],
                $clientTokenStatusIndex
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Subscription::TABLE);
    }
}
