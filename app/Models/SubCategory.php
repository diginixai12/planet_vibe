<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class SubCategory extends Model
{
    use HasFactory;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'sub_category', 'description', 'monthly_days', 'monthly_price', 'quarterly_days', 'quarterly_price', 'halfyearly_days', 'halfyearly_price', 'is_new', 'is_popular', 'image', 'created_at', 'updated_at', 'deleted_at', 'status',
    ];
}
