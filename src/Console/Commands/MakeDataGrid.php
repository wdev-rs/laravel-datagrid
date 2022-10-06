<?php

namespace WdevRs\LaravelDatagrid\Console\Commands;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeDataGrid extends \Illuminate\Console\GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:datagrid {name} {--M|model=} {--F|fields=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new datagrid';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'DataGrid';

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return string
     */
    protected function replaceClass($stub, $name): string
    {
        $className = $this->argument('name');
        $model = $this->option("model");
        $fields = $this->option("fields");

        $stub = str_replace('DummyDataGrid', $className, $stub);
        $stub = str_replace('DummyModel', $model, $stub);
        $stub = str_replace("->column();", $this->makeColumns($fields), $stub);

        return $stub;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/Stubs/make-data-grid.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\DataGrids';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the datagrid.'],
        ];
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Make columns
     *
     * @param string $fields
     * @return string
     */
    protected function makeColumns(string $fields): string
    {
        $columns = "";
        foreach (explode(",", $fields) as $field) {
            $label = $field;
            if (Str::contains($fields, ":")) {
                list($label, $field) = explode(":", $field);
            }

            $columns .= "            ->column('{$field}', '{$label}')\n";
        }
        $columns .= substr($columns, 0, -2).";";

        return $columns;
    }
}
