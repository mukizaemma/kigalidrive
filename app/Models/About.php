<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $table = 'abouts';

    protected $fillable = [
        'title',
        'subTitle',
        'welcomeMessage',
        'WhyChooseUs',
        'mission',
        'vision',
        'what_we_do',
        'commitment',
        'background',
        'core_values',
        'team',
        'rate',
        'image1',
        'image2',
        'image3',
        'image4',
        'cta_services_url',
        'cta_book_url',
        'cta_contact_url',
        'user_id',
    ];
}
