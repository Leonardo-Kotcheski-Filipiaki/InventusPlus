<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 */
#[Table('stock_category')]
#[Fillable(['name', 'description'])]
class StockCategory extends Model
{
    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class, 'stock_category_id');
    }
}
