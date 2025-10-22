<?php

namespace App\DTO;

class ProductImportData
{
    public function __construct(
        public readonly ?string $categoryLevel1,
        public readonly ?string $categoryLevel2,
        public readonly ?string $categoryLevel3,
        public readonly ?string $manufacturer,
        public readonly string $name,
        public readonly string $modelCode,
        public readonly ?string $description,
        public readonly ?float $priceUah,
        public readonly int $warrantyMonths,
        public readonly ?bool $isAvailable,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            categoryLevel1: self::normalizeString($row[0] ?? null),
            categoryLevel2: self::normalizeString($row[1] ?? null),
            categoryLevel3: self::normalizeString($row[2] ?? null),
            manufacturer: self::normalizeString($row[3] ?? null),
            name: self::normalizeString($row[4] ?? '') ?:
                throw new \InvalidArgumentException('Product name is required'),
            modelCode: strtoupper(self::normalizeString($row[5] ?? '') ?:
                throw new \InvalidArgumentException('Model code is required')),
            description: self::normalizeDescription($row[6] ?? null),
            priceUah: self::normalizePrice($row[7] ?? null),
            warrantyMonths: self::normalizeWarranty($row[8] ?? null),
            isAvailable: self::normalizeAvailability($row[9] ?? null),
        );
    }

    private static function normalizeString(?string $value): ?string
    {
        $trimmed = trim((string) ($value ?? ''));

        return $trimmed === '' ? null : $trimmed;
    }

    private static function normalizeDescription(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private static function normalizePrice(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        $cleaned = str_replace([' ', ','], ['', ''], (string) $value);

        return is_numeric($cleaned) ? (float) $cleaned : null;
    }

    private static function normalizeWarranty(mixed $value): int
    {
        if (! $value || mb_strtolower((string) $value) === 'нет') {
            return 0;
        }

        return (int) preg_replace('/\D+/', '', (string) $value);
    }

    private static function normalizeAvailability(mixed $value): ?bool
    {
        $normalized = mb_strtolower(trim((string) $value));
        if ($normalized === '') {
            return null;
        }
        if (str_contains($normalized, 'есть')) {
            return true;
        }
        if ($normalized === 'нет') {
            return false;
        }

        return null;
    }
}
