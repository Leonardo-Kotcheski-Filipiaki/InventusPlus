<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $street
 * @property string $number
 * @property string $neighborhood
 * @property string|null $complement
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property string|null $created_at
 * @property string|null $updated_at
 */
#[Fillable('street', 'number', 'neighborhood', "complement", 'city', 'state', 'zip_code')]
#[Table('address')]
class Address extends Model
{
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'address_id', 'id');
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class, 'address_id', 'id');
    }
}
