<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['user_id','listing_id','review','rating','review_date'];
    public $timestamps = false;

    // public static function ratings($productid){
    //     $stars = Rating::where('product_id',$productid)->avg('rating');
    //     $ratings = number_format((float)$stars, 1, '.', '')*20;
    //     return $ratings;
    // }
    // public static function rating($productid){
    //     $stars = Rating::where('product_id',$productid)->avg('rating');
    //     $stars = number_format((float)$stars, 1, '.', '');
    //     return $stars;
    // }

}
