<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $cpf
 * @property Carbon|null $birthdate
 * @property string|null $email
 * @property string|null $phone
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'cpf', 'birthdate', 'email', 'phone'])]
#[Table("intra_person")]
class IntraPerson extends Model
{
    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }
}
