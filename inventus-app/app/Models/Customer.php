<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int id
 * @property int person_id
 * @property int address_id
 * @property string created_at
 * @property string updated_at
 */
#[Table('customer')]
#[Fillable('person_id', 'address_id')]
class Customer extends Model
{
    public function person() : HasOne
    {
        return $this->hasOne(Person::class, 'id', 'person_id');
    }

    public function address() : HasOne
    {
        return $this->hasOne(Address::class, 'id', 'address_id');
    }
}
