<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string|null $description
 * @property int|null $supplier_id
 * @property int|null $customer_id
 * @property int $stock_id
 * @property int $quantity
 * @property string $unit_value
 * @property string $sales_type
 * @property string|null $created_at
 * @property string|null $updated_at
 */
#[Table('sales')]
#[Fillable(['description', 'supplier_id', 'customer_id', 'stock_id', 'quantity', 'unit_value', 'sales_type'])]
class Sales extends Model
{
    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
