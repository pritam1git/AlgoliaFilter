<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\AlgoliaLogs;
class AddRecordsToAlgolia extends Command
{
    protected $signature = 'algolia:add-records';
    protected $description = 'Add records to Algolia';

    public function handle()
    {
        // dd(AlgoliaLogs::all());
        try {
            // Algolia credentials
            $algoliaAppId = env('ALGOLIA_APP_ID');
            $algoliaApiKey = env('ALGOLIA_API_KEY');
            $indexName = env('ALGOLIA_INDEX_NAME');
            $filenames = []; // To store all filenames
            // Algolia client using the Algolia PHP SDK
            $algoliaClient = \Algolia\AlgoliaSearch\SearchClient::create($algoliaAppId, $algoliaApiKey);
            $index = $algoliaClient->initIndex($indexName);
    
            // Step 1: Clear the index using the official Algolia SDK
            $index->clearObjects();
            $this->info('All existing records deleted from Algolia index.');
    
            // Step 2: Add new records from JSON files
            $fileIndex = 0;
    
            while (true) {
                // Adjust file path as per your requirement
                $filePath = 'json/coupons_data_' . $fileIndex . '.json';
    
                if (!Storage::disk('admin')->exists($filePath)) {
                    break; // Exit loop when no more files
                }
                $filenames[] = 'coupons_data_' . $fileIndex . '.json';
                // Fetch and decode the JSON data
                $json = Storage::disk('admin')->get($filePath);
                $data = json_decode($json, true); // Convert JSON to PHP array
    
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->error('Error decoding JSON from file: ' . $filePath);
                    break;
                }
    
                if (empty($data)) {
                    $this->error('No data found in file: ' . $filePath);
                    break;
                }
    
                // Use Algolia SDK to batch insert data
                $index->saveObjects($data, ['autoGenerateObjectIDIfNotExist' => true]);
                $this->info("Records from coupons_data_{$fileIndex}.json added successfully.");
    
                $fileIndex++;
            }
            AlgoliaLogs::create([
                'filename' => implode(',', $filenames),
                'flag' => 0,
            ]);
            $this->info('All records processed and added to Algolia.');
        } catch (\Algolia\AlgoliaSearch\Exceptions\AlgoliaException $e) {
            $this->error('Algolia error: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->error('Error adding records: ' . $e->getMessage());
        }
    }
    
    
    
}
