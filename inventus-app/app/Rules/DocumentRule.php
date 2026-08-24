<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DocumentRule implements ValidationRule
{
    protected string $type;
    // Construtor para receber 'cpf', 'cnpj' ou 'both' (ambos)
    public function __construct(string $type = 'both')
    {
        $this->type = $type;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cleanValue = preg_replace('/[^a-zA-Z0-9]/', '', $value);
        if (ctype_digit($cleanValue)) {
            // Se estou esperando um CPF ou AMBOS
            if (strlen($cleanValue) === 11 && in_array($this->type, ['cpf', 'both'])) {
                if (!$this->validateCPF($cleanValue)) {
                    $fail("O {$attribute} não é um CPF válido.");
                }
                return;
            } 
            
            // Se estou esperando um CNPJ ou AMBOS
            if (strlen($cleanValue) === 14 && in_array($this->type, ['cnpj', 'both'])) {
                if (!$this->validateCNPJ($cleanValue)) {
                    $fail("O {$attribute} não é um CNPJ válido.");
                }
                return;
            }
            
            $fail("O {$attribute} não possui um tamanho numérico válido para este campo.");
            return;
        }
        if (strlen($cleanValue) < 5 || strlen($cleanValue) > 20) {
            $fail("O {$attribute} alfanumérico deve ter entre 5 e 20 caracteres.");
        }
    }

    private function validateCPF(string $cpf): bool
    {
        // Extrai apenas os números
        $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
        
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) return false;

        // Verifica se foi informada uma sequência de digitos repetidos (Ex: 111.111.111-11)
        if (preg_match('/(\d)\1{10}/', $cpf)) return false;

        // Faz o calculo matemático para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    private function validateCNPJ(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        if (strlen($cnpj) != 14) return false;
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;
        
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) return false;
        
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }
}

