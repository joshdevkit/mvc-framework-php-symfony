<?php

namespace App\Util;

use App\Models\User;

class Validator
{
    protected array $errors = [];

    public function validate(array $input, array $rules)
    {
        foreach ($rules as $field => $rule) {
            $rulesArray = explode('|', $rule);
            foreach ($rulesArray as $rule) {
                $ruleDetails = explode(':', $rule);
                $ruleName = $ruleDetails[0];
                $ruleValue = $ruleDetails[1] ?? null;

                switch ($ruleName) {
                    case 'required':
                        $this->validateRequired($field, $input[$field] ?? null);
                        break;
                    case 'min':
                        $this->validateMin($field, $input[$field] ?? null, $ruleValue);
                        break;
                    case 'max':
                        $this->validateMax($field, $input[$field] ?? null, $ruleValue);
                        break;
                    case 'confirm_password':
                        $this->validateConfirmPassword($field, $input['password'] ?? null, $input['confirm_password'] ?? null);
                        break;
                    case 'unique':
                        $this->validateUnique($field, $input[$field] ?? null, $ruleValue);
                        break;
                    default:
                        throw new \InvalidArgumentException('Invalid validation rule: ' . $ruleName);
                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    protected function validateRequired(string $field, $value): void
    {
        if (empty($value)) {
            $this->errors[$field][] = 'The ' . $this->formatFieldName($field) . ' is required.';
        }
    }

    protected function validateMin(string $field, $value, $min): void
    {
        if (strlen($value) < $min) {
            $this->errors[$field][] = 'The ' . $this->formatFieldName($field) . ' must be at least ' . $min . ' characters.';
        }
    }

    protected function validateMax(string $field, $value, $max): void
    {
        if (strlen($value) > $max) {
            $this->errors[$field][] = 'The ' . $this->formatFieldName($field) . ' must be less than ' . $max . ' characters.';
        }
    }

    protected function validateConfirmPassword(string $field, $password, $confirmPassword): void
    {
        if ($password !== $confirmPassword) {
            $this->errors[$field][] = 'The ' . $this->formatFieldName($field) . ' does not match.';
        }
    }

    protected function validateUnique(string $field, $value, $table): void
    {
        $user = User::find('email', $value);

        if ($user) {
            $this->errors[$field][] = 'This ' . $this->formatFieldName($field) . ' has already been taken.';
        }
    }

    protected function formatFieldName(string $field): string
    {
        return ucfirst(str_replace('_', ' ', $field));
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
