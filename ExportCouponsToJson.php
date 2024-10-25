<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportCouponsToJson extends Command
{
    // The name and signature of the command
    protected $signature = 'export:coupons-json';

    // The command description
    protected $description = 'Export coupons data to a JSON file';

    // Execute the console command
    public function handle()
    {
        $chunkSize = 100000;  // Number of records to fetch at once
        $fileIndex = 0;  // JSON file index
        $lastId = 0;  // Starting point for the query (id)
        $totalCouponsProcessed = 0;  // Counter for total coupons processed
        $jsonDirectory = 'json';
    
        // Step 1: Check if any files exist in the 'json' directory and delete them
        if (Storage::disk('admin')->exists($jsonDirectory)) {
            $files = Storage::disk('admin')->files($jsonDirectory);
            foreach ($files as $file) {
                Storage::disk('admin')->delete($file);
            }
            $this->info("Deleted existing files in 'json' directory.");
        } else {
            // Create the 'json' directory if it doesn't exist
            Storage::disk('admin')->makeDirectory($jsonDirectory);
        }
    
        // Step 2: Reset last processed ID (if there was any)
        $lastId = 0;
    
        // Step 3: Start generating new JSON files
        do {
            $chunk = [];
    
            // Fetch data in chunks, filtering by 'active' status
            $coupons = DB::table('couponData_all')
                ->where('id', '>', $lastId)
                ->orderBy('id')
                ->limit($chunkSize)
                ->get();
    
            // If no more coupons, exit the loop
            if ($coupons->isEmpty()) {
                break;
            }
    
            // Process each coupon and add to the chunk
            foreach ($coupons as $coupon) {
                $categoryName = $coupon->CategoryName ?? null;
                $categorySlug = $coupon->cat_slug ?? null;
    
                $chunk[] = [
                    'CategoryName' => $categoryName,
                    'BrandName' => $coupon->BrandName ?? null,
                    'affiliate_link' => $coupon->affiliate_link,
                    'brand_id' => $coupon->brand_id,
                    'brand_image' => $coupon->brand_image ?? null,
                    'brand_slug' => $coupon->brand_slug ?? null,
                    'cat_slug' => $categorySlug,
                    'category_id' => $coupon->category_id,
                    'coupon_code' => $coupon->coupon_code,
                    'coupon_desc' => $coupon->coupon_desc,
                    'id' => $coupon->id,
                    'yes_count' => $coupon->yes_count
                ];
    
                // Update the last processed ID
                $lastId = $coupon->id;
            }
    
            // Step 4: Write JSON file for the current chunk
            if (!empty($chunk)) {
                $filePath = $jsonDirectory . '/coupons_data_' . $fileIndex . '.json';
                Storage::disk('admin')->put($filePath, json_encode($chunk, JSON_PRETTY_PRINT));
                $fileIndex++;
    
                // Save the last processed ID to a file
                Storage::disk('admin')->put($jsonDirectory . '/last_processed_id.txt', $lastId);
    
                // Increment total coupons processed
                $totalCouponsProcessed += count($chunk);
            }
    
        } while (count($chunk) === $chunkSize);
    
        // Step 5: Output the completion message
        $this->info("Process completed successfully. Total Coupons Processed: " . $totalCouponsProcessed);
    }
    
}
