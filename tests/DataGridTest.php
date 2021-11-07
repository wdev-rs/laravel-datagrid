<?php

namespace WdevRs\LaravelDatagrid\Tests;

use Symfony\Component\VarDumper\Cloner\Data;
use WdevRs\LaravelDatagrid\DataGrid\DataGrid;
use WdevRs\LaravelDatagrid\LaravelDatagridServiceProvider;
use WdevRs\LaravelDatagrid\Tests\Models\TestProduct;

class DataGridTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [LaravelDatagridServiceProvider::class];
    }

    /**
     * @test
     *
     * @dataProvider provideDataSources
     */
    public function it_should_search_by_name(string $source)
    {
        TestProduct::factory()->count(10)->create();
        $product = TestProduct::factory()->create(['name' => 'This is the test product']);

        $dataGrid = $this->createDataGrid($source);

        $this->createRequest(['search' => 'product']);

        $result = $dataGrid->render();
        $this->assertCount(1, $result['data']);
        $this->assertEquals($product->name, $result['data'][0]['name']);
    }

    /**
     * @test
     *
     * @dataProvider provideDataSources
     */
    public function it_should_order_by_name(string $source)
    {
        $productZ = TestProduct::factory()->create(['name' => 'This is the Z product']);
        $productA = TestProduct::factory()->create(['name' => 'This is the A product']);
        $productB = TestProduct::factory()->create(['name' => 'This is the B product']);

        $dataGrid = $this->createDataGrid($source);

        $this->createRequest(['order' => ['name'], 'dir' => ['asc']]);

        $result = $dataGrid->render();

        $this->assertCount(3, $result['data']);
        $this->assertEquals($productA->name, $result['data'][0]['name']);
        $this->assertEquals($productB->name, $result['data'][1]['name']);
        $this->assertEquals($productZ->name, $result['data'][2]['name']);
    }
    /**
     * @test
     *
     * @dataProvider provideDataSources
     */
    public function it_should_paginate_the_results(string $source)
    {
        TestProduct::factory()->count(100)->create();

        $dataGrid = $this->createDataGrid($source);

        $this->createRequest();

        $result = $dataGrid->render();

        $this->assertCount(15, $result['data']);
        $this->assertEquals(100, $result['total']);
    }

    /**
     * @test
     *
     * @dataProvider provideDataSources
     */
    public function it_should_get_the_correct_page(string $source)
    {
        $products = TestProduct::factory()->count(100)->create();

        $dataGrid = $this->createDataGrid($source);

        $this->createRequest(['page' => 2, 'limit' => 15]);

        $result = $dataGrid->render();

        $this->assertEquals($products[15]->id, $result['data'][0]['id']);
        $this->assertEquals(100, $result['total']);
    }

    public function provideDataSources() {
        return [
            ['query'],
            ['collection']
        ];
    }

    private function createRequest(array $params = []): void
    {
        $request = request();
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        $request->merge($params);
    }

    protected function createDataGrid($source): DataGrid
    {
        $dataGrid = new DataGrid();
        $dataGrid->column('id', 'id')
            ->column('name', 'name');

        switch ($source){
            case 'query':
                return  $dataGrid->query(TestProduct::query());
            case 'collection':
                return $dataGrid->collection(TestProduct::query()->get());
        }

        return $dataGrid;
    }
}
