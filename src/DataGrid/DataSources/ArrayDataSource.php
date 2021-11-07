<?php

namespace WdevRs\LaravelDatagrid\DataGrid\DataSources;

use Illuminate\Container\Container;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ArrayDataSource extends CollectionDataSource implements DataSourceContract
{
    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct(collect($data));
    }
}
