<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string|null $cpf
 * @property string|null $cnpj
 * @property string|null $email
 * @property string|null $phone
 * @property int|null $address_id
 * @property string|null $created_at
 * @property string|null $updated_at
 */
#[Table('supplier')]
#[Fillable(['name', 'cpf', 'cnpj', 'email', 'phone', 'address_id'])]
class Supplier extends Model
{
    protected function cpf(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? preg_replace('/[^a-zA-Z0-9]/', '', $value) : null,
            get: fn (?string $value) => $value ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value) : null,
        );
    }

    protected function cnpj(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? preg_replace('/[^a-zA-Z0-9]/', '', $value) : null,
            get: fn (?string $value) => $value ? preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $value) : null,
        );
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? preg_replace('/[^a-zA-Z0-9]/', '', $value) : null,
            get: fn (?string $value) => $value ? preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $value) : null,
        );
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
