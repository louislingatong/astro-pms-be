<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserSearchTest extends TestCase
{
    use WithoutMiddleware;

    /** @var array */
    private static $ADMIN;

    /** @var string */
    private static $ACCESS_TOKEN;

    /** @var string */
    private static $KEYWORD = 'as';

    /** @var array */
    private static $USERIDS = [];

    /** @var int */
    private static $TOTAL;

    /** @var int */
    private static $LASTPAGE;

    public function setUp(): void
    {
        parent::setUp();

        // set admin details
        self::$ADMIN = [
            'email' => 'admin@astro.ph',
            'password' => 'P@ssw0rd',
        ];

        // Login as Admin once only
        if (!self::$ACCESS_TOKEN) {
            $response = $this->json(
                'POST',
                '/' . config('app.api_version') . '/oauth/token',
                [
                    'client_id' => (integer)config('app.client_id'),
                    'client_secret' => config('app.client_secret'),
                    'grant_type' => 'password',
                    'username' => self::$ADMIN['email'],
                    'password' => self::$ADMIN['password'],
                ]
            );
            $result = json_decode((string)$response->getContent());
            // store access token to be used in testing
            self::$ACCESS_TOKEN = $result->access_token;
        }
    }

    /**
     * UserSearchTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->createApplication();
    }

    public function testSearchNoResults()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . self::$ACCESS_TOKEN,
        ])
            ->json('GET', '/' . config('app.api_version') . '/users?keyword=randomString');
        $response->assertStatus(200);
        $result = json_decode((string)$response->getContent());
        $this->assertEquals(0, count($result->data));
        $this->assertEquals(0, $result->meta->total);
        $this->assertEquals(1, $result->meta->lastPage);
        $this->assertEquals(null, $result->meta->previousPageUrl);
        $this->assertEquals(null, $result->meta->nextPageUrl);
    }

    public function testSearchByKeyword()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . self::$ACCESS_TOKEN,
        ])
            ->json('GET', '/' . config('app.api_version') . '/users?keyword=' . self::$KEYWORD);
        $response->assertStatus(200);
        $result = json_decode((string)$response->getContent());

        // verify if the keyword exists either in first name, last name or email
        foreach ($result->data as $user) {
            $hasKeyword = false;

            if (strpos(strtolower($user->first_name), self::$KEYWORD) !== false) {
                $hasKeyword = true;
            }

            if (strpos(strtolower($user->last_name), self::$KEYWORD) !== false) {
                $hasKeyword = true;
            }

            if (strpos(strtolower($user->email), self::$KEYWORD) !== false) {
                $hasKeyword = true;
            }

            $this->assertTrue($hasKeyword);
        }

        // verify by default starting page is 1
        $this->assertEquals(1, $result->meta->currentPage);

        // verify default search limit
        $this->assertEquals(config('search.results_per_page'), $result->meta->perPage);

        // store total for limit testing
        self::$TOTAL = $result->meta->total;
    }

    public function testSearchWithLimit()
    {
        $limit = floor(self::$TOTAL / 2);

        // initialize query
        $query = [
            'limit' => $limit,
            'keyword' => self::$KEYWORD,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . self::$ACCESS_TOKEN,
        ])
            ->json('GET', '/' . config('app.api_version') . '/users?' . http_build_query($query));
        $response->assertStatus(200);
        $result = json_decode((string)$response->getContent());

        // verify limit matches the data per page
        $this->assertEquals($limit, $result->meta->perPage);
        $this->assertEquals($limit, count($result->data));

        // store last page for next test
        self::$LASTPAGE = $result->meta->lastPage;
    }

    public function testSearchByPage()
    {
        $this->markTestIncomplete('PHPUnit result does not match the result in browser.');

        $limit = floor(self::$TOTAL / 2);
        $page = 1;

        while ($page <= self::$LASTPAGE) {
            // initialize query
            $query = [
                'limit' => $limit,
                'keyword' => self::$KEYWORD,
                'page' => $page,
            ];

            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . self::$ACCESS_TOKEN,
            ])
                ->json('GET', '/' . config('app.api_version') . '/users?' . http_build_query($query));
            $response->assertStatus(200);
            $result = json_decode((string)$response->getContent());

            // verify page matches
            $this->assertEquals($page, $result->meta->currentPage);

            // verify page has results
            $this->assertTrue(count($result->data) > 0);

            $page = $result->meta->currentPage + 1;
        }
    }
}
