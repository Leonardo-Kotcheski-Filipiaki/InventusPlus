<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

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
#[Fillable('street', 'number', 'neighborhood', 'city', 'state', 'zip_code')]
class Address extends Model
{
}
