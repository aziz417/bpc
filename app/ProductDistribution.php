<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $distribution_id)
 */
class ProductDistribution extends Model
{
    protected $fillable = ['seller_id', 'product_id', 'stock_id', 'branch_type_id', 'branch_id', 'quantity', 'date'];
}
