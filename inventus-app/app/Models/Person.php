<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $cpf
 * @property string|null $cnpj
 * @property Carbon|null $birthdate
 * @property string|null $email
 * @property string|null $phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'cpf', 'cnpj', 'birthdate', 'email', 'phone'])]
#[Table("person")]
class Person extends Model
{
    protected function casts(): array
    {   
        return [
            'birthdate' => 'date:Y-m-d',
        ];
    }
    // Para o campo CPF
    protected function cpf(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? preg_replace('/[^a-zA-Z0-9]/', '', $value) : null,
            get: fn (?string $value) => $value ? preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value) : null,
        );
    }
    // Para o campo CNPJ
    protected function cnpj(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? preg_replace('/[^a-zA-Z0-9]/', '', $value) : null,
            get: fn (?string $value) => $value ? preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $value) : null,
        );
    }

    // Para o campo phone
    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? preg_replace('/[^a-zA-Z0-9]/', '', $value) : null,
            get: fn (?string $value) => $value ? preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $value) : null,
        );
    }

    

}
