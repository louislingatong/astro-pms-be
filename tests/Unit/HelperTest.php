<?php

namespace Tests\Unit;

use App\Http\Resources\UserResource;
use App\Models\User;
use Tests\TestCase;

class HelperTest extends TestCase
{
    /**
     * @expectedException Exception
     * @expectedExceptionMessage Parameter results must be from an eloquent paginate query.
     */
    public function testPaginatedInvalidResultsType()
    {
        paginated([], UserResource::class, 1, []);
    }

    public function testPaginated()
    {
        $result = paginated(User::paginate(10), UserResource::class, 1, ['limit' => 10, 'keyword' => 'a']);

        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('meta', $result);
        $this->assertArrayHasKey('total', $result['meta']);
        $this->assertArrayHasKey('currentPage', $result['meta']);
        $this->assertArrayHasKey('lastPage', $result['meta']);
        $this->assertArrayHasKey('perPage', $result['meta']);
        $this->assertArrayHasKey('previousPageUrl', $result['meta']);
        $this->assertArrayHasKey('nextPageUrl', $result['meta']);
        $this->assertArrayHasKey('url', $result['meta']);
    }
}
