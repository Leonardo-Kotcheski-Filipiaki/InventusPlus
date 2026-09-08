<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $quantity
 * @property string $unit_value
 * @property int $supplier_id
 * @property int $stock_category_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
#[Table('stock')]
#[Fillable(['name', 'description', 'quantity', 'unit_value', 'supplier_id', 'stock_category_id'])]
class Stock extends Model
{
    public function stockCategory(): BelongsTo
    {
        return $this->belongsTo(StockCategory::class, 'stock_category_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
