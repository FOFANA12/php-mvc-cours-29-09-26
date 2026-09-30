<?php

namespace App\Core;

class Validator
{
    private array $errors = [];

    public function __construct(private array $data) {}

    public function required(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? null;

        if (is_null($value) || (is_string($value) && trim($value) === '')) {
            $this->addError($field, $message ?? "Le champ $field est obligatoire.");
        }

        return $this;
    }


    public function min(string $field, int $min, ?string $message = null): self
    {
        $value = trim((string) ($this->data[$field] ?? ''));
        if (strlen($value) < $min) {
            $this->addError($field, $message ?? "Le champ $field doit être au moins $min caractères.");
        }

        return $this;
    }

    public function max(string $field, int $max, ?string $message = null): self
    {
        $value = trim((string) ($this->data[$field] ?? ''));
        if (strlen($value) > $max) {
            $this->addError($field, $message ?? "Le champ $field ne doit dépasser $max caractères.");
        }

        return $this;
    }

    public function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
