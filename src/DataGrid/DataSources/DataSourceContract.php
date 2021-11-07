<?php

namespace WdevRs\LaravelDatagrid\DataGrid\DataSources;

use Illuminate\Contracts\Pagination\Paginator;

interface DataSourceContract
{
    public function search(?string $search, array $columns): void;

    public function sort(?array $orders, ?array $dirs): void;

    public function paginate($perPage, $columns, $pageName, $page): Paginator;
}
