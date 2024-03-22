<?php

namespace WdevRs\LaravelDatagrid\DataGrid;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use WdevRs\LaravelDatagrid\DataGrid\DataSources\ArrayDataSource;
use WdevRs\LaravelDatagrid\DataGrid\DataSources\CollectionDataSource;
use WdevRs\LaravelDatagrid\DataGrid\DataSources\DataSourceContract;
use WdevRs\LaravelDatagrid\DataGrid\DataSources\QueryDataSource;
use WdevRs\LaravelDatagrid\LaravelDatagrid;

class DataGrid
{
    protected Builder $query;
    protected DataSourceContract $dataSource;
    protected array $columns;
    protected array $formatters;
    protected string $key = 'id';

    public function fromQuery(Builder $query): self
    {
        $this->dataSource = app(QueryDataSource::class, ['query' => $query]);

        return $this;
    }

    public function fromCollection(Collection $data): self
    {
        $this->dataSource = app(CollectionDataSource::class, ['data' => $data]);

        return $this;
    }

    public function fromArray(array $data): self
    {
        $this->dataSource = app(ArrayDataSource::class, ['data' => $data]);

        return $this;
    }

    public function column(string $id, string $name, $formatter = null, ?string $width = null, bool $sortable = true, bool $searchable = true): self
    {
        $this->columns[] = [
            'id' => $id,
            'name' => $name,
            'width' => $width,
            'sortable' => $sortable,
            'searchable' => $searchable
        ];

        $this->formatters[$id] = $formatter;

        return $this;
    }

    public function key(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function render(string $view = 'laravel-datagrid::datagrid')
    {
        $request = request();

        if ($request->expectsJson()) {
            return $this->getData($request);
        }

        switch (config('laravel-datagrid.render_with')) {
            case LaravelDatagrid::RENDER_INERTIA:
                return \Inertia\Inertia::render($view, [
                    'baseUrl' => $request->url(),
                    'columns' => $this->columns,
                    'rows' => $this->getData($request)
                ]);

            case LaravelDatagrid::RENDER_BLADE:
            default:
                return view($view, [
                    'baseUrl' => $request->url(),
                    'columns' => $this->columns,
                    'rows' => $this->getData($request)
                ]);
            };
    }

    protected function format($data)
    {
        return collect($data)->map(function ($item) {
            $formatted = is_array($item) ? $item : $item->toArray();
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

    /**
     * @param $request
     * @return array
     */
    private function getData($request): array
    {
        $paginator = $this->search($request->search)
            ->sort($request->order, $request->dir)
            ->paginate($request->limit)
            ->withQueryString();

        return [
            'key' => $this->key,
            'data' => $this->format($paginator->items()),
            'total' => $paginator->total(),
            'currentPage' => $paginator->currentPage(),
            'search' => $request->search,
            'order' => $request->order,
            'dir' => $request->dir,
            'limit' => $paginator->perPage(),
            'paginationLinks' => $paginator->linkCollection()->toArray()
        ];
    }
}
