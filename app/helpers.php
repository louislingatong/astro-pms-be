<?php

use Illuminate\Pagination\LengthAwarePaginator;

/*
 * Custom Paginate helper. Meta will adjust the
 * paginated urls based on the params provided
 * during search.
 */
if (!function_exists('paginated')) {
    /**
     * @param LengthAwarePaginator $results
     * @param mixed $resource
     * @param int $page
     * @param array $params
     * @return array
     * @throws
     */
    function paginated($results, $resource, $page = 1, $params = [])
    {
        if (!($results instanceof LengthAwarePaginator)) {
            throw new Exception('Parameter results must be from an eloquent paginate query.');
        }

        // set the custom url params
        $urlParams = http_build_query($params);

        $nextPageUrl = ($results->nextPageUrl()) ? $results->nextPageUrl() . '&' . $urlParams : null;
        $previousPageUrl = ($results->previousPageUrl()) ? $results->previousPageUrl() . '&' . $urlParams : null;

        return [
            'data' => $resource::collection($results),
            'meta' => [
                'total' => $results->total(),
                'current_page' => $page,
                'last_page' => $results->lastPage(),
                'per_page' => $results->perPage(),
                'previous_page_url' => $previousPageUrl,
                'next_page_url' => $nextPageUrl,
                'url' => $results->url($page) . '&' . $urlParams,
            ],
        ];
    }
}
