<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Path (relative to database/seeders) of the BOM CSV.
     */
    private const CSV_FILE = 'bill-of-materials.csv';

    public function run(): void
    {
        $path = database_path('seeders/' . self::CSV_FILE);

        if (! file_exists($path)) {
            $this->command->error("CSV not found at {$path}");
            return;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->command->error("Cannot open CSV at {$path}");
            return;
        }

        $header = fgetcsv($handle);
        
        // clean header
        if ($header && isset($header[0])) {
            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
        }

        // 2) Read rows
        while (($row = fgetcsv($handle)) !== false) {
            $row = array_map('trim', $row);
            $record = array_combine($header, $row);

            Product::create([
                'product_name'  => $record['Product']               ?? '',
                'mpn'           => $record['MPN']                   ?: null,
                'sku'           => $record['SKU']                   ?: null,
                'trade_price'   => $this->toDecimal($record['Total Trade Price'] ?? ''),
                'retail_price'  => $this->toDecimal($record['Total Retail Price'] ?? ''),
                'quantity'      => (int) ($record['Quantity']       ?? 0),
            ]);
        }

        fclose($handle);
        $this->command->info('Products seeded successfully.');
    }

    /**
     * Normalize a price string like "$1,234.56" to float 1234.56.
     */
    private function toDecimal(string $value): float
    {
        // strip anything except digits and dot
        $num = preg_replace('/[^\d.]/', '', $value);
        return $num === '' ? 0.0 : (float) $num;
    }
}
