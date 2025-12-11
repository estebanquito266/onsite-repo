<?php

namespace App\Services\Onsite;

use Illuminate\Support\Facades\Http;

class AzureBlobUploader
{
    protected string $containerUrl;
    protected string $sasToken;

    public function __construct()
    {
        $this->containerUrl = config('azure.container_url');
        $this->sasToken     = config('azure.sas_token');
    }

   
    public function upload($localPath, $fileName=null)
    {
        if (!file_exists($localPath)) {
            return false;
        }

        $fileName = $fileName ?? basename($localPath);

        $url = "{$this->containerUrl}/{$fileName}?{$this->sasToken}";

       

        $response = Http::withHeaders([
            'x-ms-blob-type' => 'BlockBlob',
        ])->withBody(
            file_get_contents($localPath),
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        )->put($url);

        if ($response->failed()) {
            return false;
        }

        @unlink($localPath);

        return $url;
    }

    public function exists(string $fileName): string|false
    {
        $url = "{$this->containerUrl}/{$fileName}?{$this->sasToken}";

        $response = Http::head($url);

        if ($response->successful()) {
            return $url;
        }

        return false;
    }
}
