<?php

namespace App\Imports;

use App\DTO\ProductImportData;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CatalogImport implements ShouldQueue, ToModel, WithBatchInserts, WithChunkReading, WithStartRow
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        try {
            $data = ProductImportData::fromRow($row);
        } catch (\InvalidArgumentException $e) {
            // Skip invalid rows
            return null;
        }

        $categoryId = $this->createCategoryTree(
            $data->categoryLevel1,
            $data->categoryLevel2,
            $data->categoryLevel3
        );
        $manufacturerId = $this->createManufacturer($data->manufacturer);

        // Avoid duplicates
        if (Product::where('model_code', $data->modelCode)->exists()) {
            return null;
        }

        return new Product([
            'category_id' => $categoryId,
            'manufacturer_id' => $manufacturerId,
            'name' => $data->name,
            'model_code' => $data->modelCode,
            'description' => $data->description,
            'price_uah' => $data->priceUah,
            'warranty_months' => $data->warrantyMonths,
            'is_available' => $data->isAvailable,
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    private function createCategoryTree(?string $level1, ?string $level2, ?string $level3): ?int
    {
        $parent = null;
        foreach ([$level1, $level2, $level3] as $level) {
            if ($level === null || $level === '') {
                continue;
            }
            $parent = Category::firstOrCreate([
                'parent_id' => $parent,
                'name' => $level,
            ])->id;
        }

        return $parent;
    }

    private function createManufacturer(?string $name): ?int
    {
        if ($name === null || $name === '') {
            return null;
        }

        return Manufacturer::firstOrCreate(['name' => $name])->id;
    }
}
