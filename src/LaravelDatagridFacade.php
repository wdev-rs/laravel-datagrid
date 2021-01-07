<?php

namespace WdevRs\LaravelDatagrid;

use Illuminate\Support\Facades\Facade;

/**
 * @see \WdevRs\LaravelDatagrid\Skeleton\SkeletonClass
 */
class LaravelDatagridFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-datagrid';
    }
}
