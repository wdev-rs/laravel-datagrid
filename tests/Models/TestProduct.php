<?php


namespace WdevRs\LaravelDatagrid\Tests\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use WdevRs\LaravelDatagrid\Tests\Database\Factories\TestProductFactory;

class TestProduct extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return new TestProductFactory();
    }
}
