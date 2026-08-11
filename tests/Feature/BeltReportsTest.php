<?php

namespace Tests\Feature;

use App\Http\Controllers\ReportsController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * The belt-flow reports build their row query and their totals query from the
 * same trunk but with different select lists, which is exactly the shape that
 * breaks quietly - a stray binding or an aggregate over an ungrouped column only
 * shows up when the query actually runs. So each one is executed here, empty and
 * filtered, rather than merely constructed.
 */
class BeltReportsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        session(['user_id' => 1]);
    }

    private function reports(): ReportsController
    {
        return new ReportsController();
    }

    /** Filters that between them exercise every branch of the filter helpers. */
    private function allFilters(): array
    {
        return [
            'batch_no' => 'ROLL',
            'product' => 'NIWAR',
            'material' => 'DHAGA',
            'cutting_no' => 'CUT',
            'roll_no' => 'R-',
            'niwar_code_id' => 1,
            'status' => 'Y',
            'from_date' => '2020-01-01',
            'to_date' => '2030-12-31',
        ];
    }

    /** @test */
    public function roll_production_report_runs_unfiltered()
    {
        $view = $this->reports()->rollProduction(new Request());

        $this->assertEquals('admin.reports.roll_production', $view->name());
        $data = $view->getData();
        $this->assertIsInt($data['totalBatches']);
        $this->assertArrayHasKey('efficiency', $data);
        // The totals query must not blow up on the row query's select subquery.
        $this->assertGreaterThanOrEqual(0, $data['totalPlanned']);
    }

    /** @test */
    public function roll_production_report_runs_with_every_filter_set()
    {
        $view = $this->reports()->rollProduction(new Request($this->allFilters()));

        $this->assertEquals('admin.reports.roll_production', $view->name());
    }

    /** @test */
    public function roll_material_consumption_report_runs_and_aggregates()
    {
        $view = $this->reports()->rollMaterialConsumption(new Request());

        $this->assertEquals('admin.reports.roll_material_consumption', $view->name());
        $data = $view->getData();

        // Variance must be the difference of the two totals it is reported beside.
        $this->assertEquals(
            round($data['totalActual'] - $data['totalPlanned'], 2),
            round($data['totalVariance'], 2)
        );

        foreach ($data['rows'] as $row) {
            $this->assertTrue(property_exists($row, 'unit'), 'Each row needs a display unit.');
            $this->assertEquals(round($row->actual_qty - $row->planned_qty, 2), round($row->variance, 2));
        }
    }

    /** @test */
    public function roll_material_consumption_report_runs_with_every_filter_set()
    {
        $view = $this->reports()->rollMaterialConsumption(new Request($this->allFilters()));

        $this->assertEquals('admin.reports.roll_material_consumption', $view->name());
    }

    /** @test */
    public function belt_cutting_report_runs_unfiltered()
    {
        $view = $this->reports()->beltCutting(new Request());

        $this->assertEquals('admin.reports.belt_cutting', $view->name());
        $data = $view->getData();
        $this->assertIsInt($data['totalPieces']);
        $this->assertGreaterThanOrEqual(0, $data['totalWastage']);
    }

    /** @test */
    public function belt_cutting_report_runs_with_every_filter_set()
    {
        $view = $this->reports()->beltCutting(new Request($this->allFilters()));

        $this->assertEquals('admin.reports.belt_cutting', $view->name());
    }

    /** @test */
    public function the_legacy_belt_production_report_still_runs()
    {
        $view = $this->reports()->beltProduction(new Request());

        $this->assertEquals('admin.reports.belt_production', $view->name());
    }

    /**
     * The register totals its meters with a database aggregate built from the
     * same builder that paginates the rows, so it is worth running rather than
     * assuming.
     *
     * @test
     */
    public function the_roll_register_runs_and_totals_in_the_database()
    {
        $controller = new \App\Http\Controllers\BeltRollProductionController();

        $view = $controller->roll_register(new Request());
        $data = $view->getData();

        $this->assertEquals('admin.belt_roll_production.register', $view->name());
        $this->assertIsInt($data['totalRolls']);
        $this->assertEquals($data['data']->total(), $data['totalRolls'], 'The summary must count the same rolls the list pages through.');

        // And again narrowed, so the filters reach the aggregate too.
        $filtered = $controller->roll_register(new Request(['status' => 'open', 'roll_no' => 'R-']));
        $this->assertEquals($filtered->getData()['data']->total(), $filtered->getData()['totalRolls']);
    }

    /** @test */
    public function the_roll_production_and_cutting_lists_run()
    {
        $rollList = (new \App\Http\Controllers\BeltRollProductionController())
            ->roll_production_list(new Request(['status' => 'Y', 'batch_no' => 'ROLL']));
        $this->assertEquals('admin.belt_roll_production.list', $rollList->name());

        $cuttingList = (new \App\Http\Controllers\BeltCuttingController())
            ->belt_cutting_list(new Request(['roll_no' => 'R-', 'cutting_no' => 'CUT']));
        $this->assertEquals('admin.belt_cutting.list', $cuttingList->name());
    }
}
