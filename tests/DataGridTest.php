<?php

namespace WdevRs\LaravelDatagrid\Tests;

use Illuminate\Pagination\PaginationServiceProvider;
use PHPUnit\Util\Test;
use Symfony\Component\VarDumper\Cloner\Data;
use WdevRs\LaravelDatagrid\DataGrid\DataGrid;
use WdevRs\LaravelDatagrid\LaravelDatagridServiceProvider;
use WdevRs\LaravelDatagrid\Tests\Models\TestProduct;

class DataGridTest extends TestCase
{
    private DataGrid $dataGrid;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dataGrid = new DataGrid();
        $this->dataGrid
            ->query(TestProduct::query())
            ->column('id', 'id')
            ->column('name', 'name');
    }

    protected function getPackageProviders($app): array
    {
        return [LaravelDatagridServiceProvider::class];
    }

    /** @test */
    public function it_should_search_by_name()
    {
        TestProduct::factory()->count(10)->create();
        $product = TestProduct::factory()->create(['name' => 'This is the test product']);

        $this->createRequest(['search' => 'product']);

        $result = $this->dataGrid->render();

        $this->assertCount(1, $result['data']);
        $this->assertEquals($product->name, $result['data'][0]['name']);
    }

    /** @test */
    public function it_should_order_by_name()
    {
        $productZ = TestProduct::factory()->create(['name' => 'This is the Z product']);
        $productA = TestProduct::factory()->create(['name' => 'This is the A product']);
        $productB = TestProduct::factory()->create(['name' => 'This is the B product']);

        $this->createRequest(['order' => ['name'], 'dir' => ['asc']]);

        $result = $this->dataGrid->render();

        $this->assertCount(3, $result['data']);
        $this->assertEquals($productA->name, $result['data'][0]['name']);
        $this->assertEquals($productB->name, $result['data'][1]['name']);
        $this->assertEquals($productZ->name, $result['data'][2]['name']);
    }

    /** @test */
    public function it_should_paginate_the_results()
    {
        TestProduct::factory()->count(100)->create();

        $this->createRequest();

        $result = $this->dataGrid->render();

        $this->assertCount(15, $result['data']);
        $this->assertEquals(100, $result['total']);
    }

    /** @test */
    public function it_should_get_the_correct_page()
    {
        $products = TestProduct::factory()->count(100)->create();
        $this->createRequest(['page' => 2, 'limit' => 15]);

        $result = $this->dataGrid->render();

        $this->assertEquals($products[15]->id, $result['data'][0]['id']);
        $this->assertEquals(100, $result['total']);
    }

    private function createRequest(array $params = []): void
    {
        $request = request();
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->merge($params);
    }
}
