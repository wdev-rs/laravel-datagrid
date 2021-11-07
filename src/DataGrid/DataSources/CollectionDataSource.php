<?php

namespace WdevRs\LaravelDatagrid\DataGrid\DataSources;

use Illuminate\Container\Container;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CollectionDataSource implements DataSourceContract
{
    protected Collection $data;
    protected Collection $processedData;

    /**
     * @param Collection $data
     */
    public function __construct(Collection $data)
    {
        $this->data = $data;
        $this->processedData = $data;
    }

    public function search(?string $search, array $columns): void
    {
        $this->processedData = $this->data->filter(function ($item) use ($search, $columns) {
            foreach ($columns as $column) {
                if (is_array($item)) {
                    $columnValue = $item[$column['id']];
                }

                if (is_object($item)) {
                    $columnValue = $item->{$column['id']};
                }

                if (Str::contains($columnValue, $search)) {
                    return true;
                };
            }
        });
    }

    public function sort(?array $orders, ?array $dirs): void
    {
        collect($orders)->each(function ($field, $index) use ($dirs) {
            if (($dirs[$index] ?? 'asc') === 'asc') {
                $this->processedData = $this->processedData->sortBy($field);
            } else {
                $this->processedData = $this->processedData->sortByDesc($field);
            }
        });
    }

    public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null): Paginator
    {
        $page = $page ?: \Illuminate\Pagination\Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: 15;

        $results = ($total = $this->processedData->count())
                ? $this->processedData->skip(($page - 1) * $perPage)->take($perPage)
                : collect();

        $results = collect($results->values());

        return $this->paginator($results, $total, $perPage, $page, [
                'path' => '/',
                'pageName' => $pageName,
            ]);
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }
}
