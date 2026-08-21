<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int person_id
 * @property int address_id
 * @property string created_at
 * @property string updated_at
 */
#[Table('costumer')]
class Costumer extends Model
{
    protected $fillable = [
        'person_id',
        'address_id',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
