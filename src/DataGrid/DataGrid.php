<?php

namespace WdevRs\LaravelDatagrid\DataGrid;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use WdevRs\LaravelDatagrid\DataGrid\DataSources\CollectionDataSource;
use WdevRs\LaravelDatagrid\DataGrid\DataSources\DataSourceContract;
use WdevRs\LaravelDatagrid\DataGrid\DataSources\QueryDataSource;

class DataGrid
{
    protected Builder $query;
    protected DataSourceContract $dataSource;
    protected array $columns;
    protected array $formatters;

    public function query(Builder $query): self
    {
        $this->dataSource = app(QueryDataSource::class, ['query' => $query]);

        return $this;
    }

    public function collection(Collection $data): self
    {
        $this->dataSource = app(CollectionDataSource::class, ['data' => $data]);

        return $this;
    }

    public function column(string $id, string $name, $formatter = null, ?string $width = null): self
    {
        $this->columns[] = [
            'id' => $id,
            'name' => $name,
            'width' => $width
        ];

        $this->formatters[$id] = $formatter;

        return $this;
    }

    public function render(string $view = 'laravel-datagrid::datagrid')
    {
        $request = request();

        if ($request->ajax()) {
            $paginator = $this->search($request->search)
                        ->sort($request->order, $request->dir)
                        ->paginate($request->limit);

            return [
                'data' => $this->format($paginator->items()),
                'total' => $paginator->total()
            ];
        }

        return view($view, [
            'baseUrl' => $request->url(),
            'columns' => $this->columns
        ]);
    }

    protected function format($data)
    {
        return collect($data)->map(function ($item) {
            $formatted = $item->toArray();
            foreach ($this->formatters as $field => $formatter) {
                if (is_callable($formatter)) {
                    $formatted[$field] = $formatter($item);
                }
            }

            return $formatted;
        });
    }

    protected function search(?string $search): self
    {
        if (!$search) {
            return $this;
        }

        $this->dataSource->search($search, $this->columns);

        return $this;
    }

    protected function sort(?array $orders, ?array $dirs): self
    {
        if (!$orders || !$dirs) {
            return $this;
        }

        $this->dataSource->sort($orders, $dirs);

        return $this;
    }

    protected function paginate($limit)
    {
        return $this->dataSource->paginate($limit);
    }
}
