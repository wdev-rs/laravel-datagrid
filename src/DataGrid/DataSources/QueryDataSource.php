<?php

declare(strict_types=1);

namespace WdevRs\LaravelDatagrid\DataGrid\DataSources;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class QueryDataSource implements DataSourceContract
{
    public Builder $query;

    /**
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function search(?string $search, array $columns): void
    {
        $this->query->where(function (Builder $query) use ($search, $columns) {
            foreach ($columns as $column) {
                if ($column['searchable']) {
                    $query->orWhere($column['id'], 'like', '%' . $search . '%');
                }
            }
        });
    }

    public function sort(?array $orders, ?array $dirs): void
    {
        collect($orders)->each(fn ($field, $index) => $this->query->orderBy($field, $dirs[$index] ?? 'asc'));
    }

    public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null): Paginator
    {
        return $this->query->paginate($perPage);
    }
}
