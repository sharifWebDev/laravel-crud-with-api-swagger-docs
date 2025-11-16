<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class BaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    /**
     * Properly handle collections.
     */
    public static function collection($resource)
    {
        return new BaseResourceCollection($resource, get_called_class());
    }
}

class BaseResourceCollection extends ResourceCollection
{
    protected $resourceClass;

    public function __construct($resource, $resourceClass = null)
    {
        parent::__construct($resource);
        $this->resourceClass = $resourceClass;
    }

    public function toArray($request)
    {
        // Transform each item with the proper resource class
        $data = $this->collection->map(function ($item) use ($request) {
            return $this->resourceClass
                ? (new $this->resourceClass($item))->toArray($request)
                : $item;
        });

        $result = ['data' => $data];

        // Handle pagination meta and links
        if ($this->resource instanceof AbstractPaginator) {
            $result['meta'] = [
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'total' => $this->total(),
                'from' => $this->firstItem(),
                'to' => $this->lastItem(),
                'path' => $this->path(),
                'first_page_url' => $this->url(1),
                'last_page_url' => $this->url($this->lastPage()),
                'next_page_url' => $this->nextPageUrl(),
                'prev_page_url' => $this->previousPageUrl(),
                'has_more_pages' => $this->hasMorePages(),
            ];

            $result['links'] = [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ];
        }

        return $result;
    }
}
