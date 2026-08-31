<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $street
 * @property string $number
 * @property string $neighborhood
 * @property string $city
 * @property string $state
 * @property string $zip_code
 * @property string $created_at
 * @property string $updated_at
 */
#[Fillable('street', 'number', 'neighborhood', "complement", 'city', 'state', 'zip_code')]
#[Table('address')]
class Address extends Model
{
    //Address have a address_id in Costumer table, how to define a relationship where Adress table have the foreign key?
    public function costumer() : BelongsTo
    {
        return $this->belongsTo(Costumer::class, 'address_id', 'id');
    }
}
