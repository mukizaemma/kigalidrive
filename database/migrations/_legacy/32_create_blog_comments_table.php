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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            
            $table->longText('comment');
            $table->string('names');
            $table->string('email');
            $table->enum('status', ['Published', 'Unpublished','Rejected'])->default('Unpublished');
            $table->dateTime('published_at')->nullable();  
            
            $table->unsignedBigInteger("added_by")->nullable();
            $table->foreign("added_by")->references("id")->on("users")->onDelete("cascade");
        
            $table->unsignedBigInteger("blog_id");
            $table->foreign("blog_id")->references("id")->on("blogs")->onDelete("cascade");
        
            $table->unsignedBigInteger("published_by")->nullable();
            $table->foreign("published_by")->references("id")->on("users")->onDelete("cascade");
        
            $table->unsignedBigInteger("approved_by")->nullable();
            $table->foreign("approved_by")->references("id")->on("users")->onDelete("set null");
        
            $table->timestamps();
            $table->softDeletes(); // optional for soft delete
        
            $table->index('blog_id');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};
