<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'reviewee_id')) {
                $table->foreignId('reviewee_id')->nullable()->after('reviewer_id')->constrained('users')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('reviews', 'reply')) {
                $table->text('reply')->nullable()->after('comment');
            }
            if (!Schema::hasColumn('reviews', 'reply_at')) {
                $table->timestamp('reply_at')->nullable()->after('reply');
            }
        });

        // Populate existing reviews reviewee_id
        $reviews = DB::table('reviews')
            ->join('claims', 'reviews.claim_id', '=', 'claims.id')
            ->join('items', 'claims.item_id', '=', 'items.id')
            ->select('reviews.id', 'items.user_id as donor_id')
            ->get();

        foreach ($reviews as $review) {
            DB::table('reviews')
                ->where('id', $review->id)
                ->update(['reviewee_id' => $review->donor_id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['reviewee_id']);
            $table->dropColumn(['reviewee_id', 'reply', 'reply_at']);
        });
    }
};
