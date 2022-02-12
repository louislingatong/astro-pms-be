<?php

namespace Tests\Unit;

use App\Traits\Uploadable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadableTraitTest extends TestCase
{
    use Uploadable;

    public function __constructor()
    {
        parent::__constructor();

        Storage::fake('public');
    }

    public function testUploadOne()
    {
        $uploadedFile = UploadedFile::fake()->create('thumbnail.jpg');

        $folder = 'avatars';

        $storedFile = $this->uploadOne($uploadedFile, $folder);

        // Assert the file was stored...
        Storage::disk('public')->assertExists($storedFile);
    }

    public function testUploadManyInvalidType()
    {
        $results = $this->uploadMany([
            'test' => 'string',
        ]);

        $this->assertEquals(
            'Must be an instance of Illuminate\Http\UploadedFile class.',
            $results['failed'][0]['error']
        );
    }

    public function testUploadMany()
    {
        $results = $this->uploadMany([
            UploadedFile::fake()->create('product1.jpg'),
            UploadedFile::fake()->create('product2.jpg'),
        ]);

        foreach ($results['successful'] as $file) {
            // Assert the file was stored...
            Storage::disk('public')->assertExists($file['fileName']);
        }
    }
}
