<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

trait Uploadable
{
    /**
     * Uploads one file in the disk
     *
     * @param Illuminate\Http\UploadedFile $uploadedFile
     * @param string $folder
     */
    public function uploadOne(UploadedFile $uploadedFile, $folder = null)
    {
        // generate random string to avoid duplicate
        $fileName = md5(uniqid() . time()) . '.' . $uploadedFile->getClientOriginalExtension();

        // define default disk
        $disk = env('STORAGE_DISK', 'public');

        return $uploadedFile->storeAs($folder, $fileName, $disk);
    }

    public function uploadMany(array $uploadedFiles, $folder = null)
    {
        // collate the results of the upload
        $files = [
            'failed' => [],
            'successful' => [],
        ];

        // define default disk
        $disk = env('STORAGE_DISK', 'public');

        foreach ($uploadedFiles as $file) {
            try {
                if (!($file instanceof UploadedFile)) {
                    throw new InvalidArgumentException('Must be an instance of Illuminate\Http\UploadedFile class.');
                }

                // get original filename, for logging error message
                $originalName = $file->getClientOriginalName();

                // generate random string to avoid duplicate
                $fileName = md5(uniqid() . time()) . '.' . $file->getClientOriginalExtension();

                $files['successful'][] = [
                    'fileName' => $file->storeAs($folder, $fileName, $disk),
                ];
            } catch (InvalidArgumentException $e) {
                $files['failed'][] = [
                    'error' => $e->getMessage(),
                ];
            } catch (Exception $e) { // @codeCoverageIgnoreStart
                // error while saving on storage runtime
                $files['failed'][] = [
                    'fileName' => $originalName,
                    'error' => $e->getMessage(),
                ];
            } // @codeCoverageIgnoreEnd
        }

        return $files;
    }
}
