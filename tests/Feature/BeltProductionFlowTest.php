<?php

namespace Tests\Feature;

use App\BeltCutting;
use App\BeltRoll;
use App\BeltRollProduction;
use App\Http\Controllers\BeltCuttingController;
use App\Http\Controllers\BeltRollProductionController;
use App\Http\Controllers\RollFormulaController;
use App\NiwarCode;
use App\RollFormulaMst;
use App\RollFormulaRevision;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

/**
 * End-to-end cover for the two-stage belt flow, driven with the exact figures
 * from the business spec:
 *
 *   Mono 10 g/mtr, Roto 12.1 g/mtr split Black 4.0 / White 3.5 / Red 2.6 /
 *   Yellow 2.0, woven into a 50 mtr roll and cut to size.
 *
 * The controllers are called directly rather than over HTTP: the admin area
 * authenticates from the session and every route lives behind it, so going
 * through the router would test the login screen rather than the production
 * logic. Everything runs inside a transaction that is rolled back, so the
 * developer database is left exactly as it was found.
 */
class BeltProductionFlowTest extends TestCase
{
    use DatabaseTransactions;

    private $niwarCodeId;
    private $monoCategoryId;
    private $rotoCategoryId;
    private $materials = [];
    private $beltProducts = [];

    /** Product family every test belt belongs to. */
    private const FAMILY = 'TEST XYZ BELT';

    protected function setUp(): void
    {
        parent::setUp();

        session(['user_id' => 1]);
        $this->seedNiwarCode();
    }

    /**
     * A PP niwar rated Mono 10 gm/mtr + Roto 12.1 gm/mtr, with a size chart, and
     * the five raw materials the spec names.
     */
    private function seedNiwarCode()
    {
        $uomId = DB::table('uom')->value('id') ?: DB::table('uom')->insertGetId(['uom_name' => 'Gram', 'website_id' => 1, 'user_id' => 1]);

        foreach (['XYZ MONO', 'XYZ ROTO', 'BLACK THREAD', 'WHITE THREAD', 'RED THREAD', 'YELLOW THREAD'] as $name) {
            $this->materials[$name] = DB::table('product')->insertGetId([
                'product_name' => 'TEST ' . $name,
                'slug' => 'test-' . strtolower(str_replace(' ', '-', $name)),
                'item_code' => 'TST-' . strtoupper(str_replace(' ', '', $name)),
                'uom' => $uomId,
                'status' => 'raw material',
                'website_id' => 1,
                'created_time' => date('d-m-Y h:i:s a'),
            ]);
        }

        $niwar = NiwarCode::create(['type' => 'TESTPP', 'code' => '99', 'rate' => 6, 'inch_per_meter' => 39.37]);
        $this->niwarCodeId = $niwar->id;

        // Mono is a plain category; Roto is a composite total filled by threads.
        $this->monoCategoryId = DB::table('niwar_type_materials')->insertGetId([
            'niwar_code_id' => $niwar->id,
            'material' => $this->materials['XYZ MONO'],
            'gm_per_meter' => 10.0,
            'is_group' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->rotoCategoryId = DB::table('niwar_type_materials')->insertGetId([
            'niwar_code_id' => $niwar->id,
            'material' => $this->materials['XYZ ROTO'],
            'gm_per_meter' => 12.1,
            'is_group' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ([['30', 32.0], ['32', 34.0], ['36', 38.0]] as [$size, $inch]) {
            DB::table('niwar_size_charts')->insert([
                'niwar_code_id' => $niwar->id,
                'pp_size' => $size,
                'required_inch' => $inch,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /** The spec's formula: Mono 10, Roto 4.0 + 3.5 + 2.6 + 2.0 = 12.1. */
    private function formulaPayload(array $overrides = []): array
    {
        return array_merge([
            'niwar_code_id' => $this->niwarCodeId,
            // The roll's own stock name is derived from this, not typed.
            'belt_product_id' => $this->defaultBeltProduct(),
            'category' => [
                $this->monoCategoryId,
                $this->rotoCategoryId, $this->rotoCategoryId, $this->rotoCategoryId, $this->rotoCategoryId,
            ],
            'material' => [
                $this->materials['XYZ MONO'],
                $this->materials['BLACK THREAD'], $this->materials['WHITE THREAD'],
                $this->materials['RED THREAD'], $this->materials['YELLOW THREAD'],
            ],
            'gm_per_meter' => [10.0, 4.0, 3.5, 2.6, 2.0],
        ], $overrides);
    }

    /**
     * The belt every formula in these tests is woven to become. Sizes created by
     * beltProductFor() belong to this same family, so the roll can be cut into
     * them - which is exactly the narrowing under test elsewhere.
     */
    private function defaultBeltProduct()
    {
        return $this->beltProductFor('30');
    }

    private function createFormula(array $overrides = []): RollFormulaMst
    {
        (new RollFormulaController())->roll_formula_store(new Request($this->formulaPayload($overrides)));

        return RollFormulaMst::latest('id')->first();
    }

    private function giveStock(array $qtyByMaterial)
    {
        foreach ($qtyByMaterial as $name => $qty) {
            DB::table('stock_status')->insert([
                'product' => $this->materials[$name],
                'qty' => $qty,
                'inward_date' => date('Y-m-d'),
                'created_time' => date('d-m-Y h:i:s a'),
            ]);
        }
    }

    private function stockOf($materialName): float
    {
        return (float) DB::table('stock_status')
            ->where('product', is_int($materialName) ? $materialName : $this->materials[$materialName])
            ->value('qty');
    }

    /** Plenty of every thread, so stock is never the thing under test. */
    private function stockEverything()
    {
        $this->giveStock([
            'XYZ MONO' => 100000, 'BLACK THREAD' => 100000, 'WHITE THREAD' => 100000,
            'RED THREAD' => 100000, 'YELLOW THREAD' => 100000,
        ]);
    }

    // ---------------------------------------------------------------- formula

    /** @test */
    public function roto_components_must_add_up_to_the_roto_total()
    {
        $this->expectException(ValidationException::class);

        // 4.0 + 3.5 + 2.6 + 2.2 = 12.3, against a Roto rated 12.1.
        $this->createFormula(['gm_per_meter' => [10.0, 4.0, 3.5, 2.6, 2.2]]);
    }

    /** @test */
    public function the_mismatch_message_names_the_category_and_the_difference()
    {
        try {
            $this->createFormula(['gm_per_meter' => [10.0, 4.0, 3.5, 2.6, 2.2]]);
            $this->fail('A Roto total of 12.3 against a rate of 12.1 should not save.');
        } catch (ValidationException $e) {
            $message = implode(' ', $e->validator->errors()->all());
            $this->assertStringContainsString('12.3', $message);
            $this->assertStringContainsString('12.1', $message);
            $this->assertStringContainsString('over by 0.2', $message);
        }
    }

    /** @test */
    public function a_balanced_formula_saves_and_freezes_version_one()
    {
        $formula = $this->createFormula();

        $this->assertEquals(1, $formula->version);
        $this->assertEquals('active', $formula->status);
        $this->assertCount(5, $formula->items);

        $revision = RollFormulaRevision::where('roll_formula_id', $formula->id)->where('version', 1)->first();
        $this->assertNotNull($revision, 'Saving a formula must freeze a version 1 revision.');
        $this->assertCount(5, $revision->items);
    }

    /** @test */
    public function editing_the_recipe_bumps_the_version_and_leaves_the_old_one_readable()
    {
        $formula = $this->createFormula();

        // Same Roto total, different colour split - a real recipe change.
        (new RollFormulaController())->roll_formula_update(new Request($this->formulaPayload([
            'id' => $formula->id,
            'gm_per_meter' => [10.0, 5.0, 2.5, 2.6, 2.0],
        ])));

        $formula->refresh();
        $this->assertEquals(2, $formula->version);

        $v1 = RollFormulaRevision::where('roll_formula_id', $formula->id)->where('version', 1)->first();
        $black = collect($v1->items)->firstWhere('material', $this->materials['BLACK THREAD']);
        $this->assertEquals(4.0, $black['gm_per_meter'], 'Version 1 must still read as it was saved.');
    }

    /** @test */
    public function re_saving_identical_numbers_does_not_invent_a_version()
    {
        $formula = $this->createFormula();

        (new RollFormulaController())->roll_formula_update(new Request($this->formulaPayload(['id' => $formula->id])));

        $this->assertEquals(1, $formula->fresh()->version);
        $this->assertEquals(1, RollFormulaRevision::where('roll_formula_id', $formula->id)->count());
    }

    /**
     * Editing a niwar code's rates must not orphan the formulas built on it -
     * roll formula rows point at the category rows by id.
     *
     * @test
     */
    public function re_saving_the_niwar_code_leaves_existing_formulas_usable()
    {
        $formula = $this->createFormula();
        $this->stockEverything();

        // Exactly what the Manage Details screen posts: no row ids, just the
        // material and its rate.
        (new \App\Http\Controllers\Admin\NiwarCodeController())->saveDetails(new Request([
            'inch_per_meter' => 39.37,
            'material' => [$this->materials['XYZ MONO'], $this->materials['XYZ ROTO']],
            'gm_per_meter' => [10.0, 12.1],
            'is_group' => [0, 1],
            'pp_size' => ['30', '32', '36'],
            'required_inch' => [32.0, 34.0, 38.0],
        ]), $this->niwarCodeId);

        $this->assertEquals(
            [$this->monoCategoryId, $this->rotoCategoryId],
            \App\NiwarTypeMaterial::where('niwar_code_id', $this->niwarCodeId)->orderBy('id')->pluck('id')->all(),
            'Category rows must keep their ids across a re-save.'
        );

        // And the formula must still be able to issue dhaga.
        $batch = $this->startBatch($formula, 50, 1);
        $this->assertEquals(500.0, $batch->materials->firstWhere('material', $this->materials['XYZ MONO'])->required_qty);
    }

    /** @test */
    public function changing_a_niwar_rate_blocks_the_formulas_that_no_longer_add_up()
    {
        $formula = $this->createFormula();
        $this->stockEverything();

        // Roto re-rated 12.1 -> 14.0, so the formula's threads no longer total it.
        (new \App\Http\Controllers\Admin\NiwarCodeController())->saveDetails(new Request([
            'inch_per_meter' => 39.37,
            'material' => [$this->materials['XYZ MONO'], $this->materials['XYZ ROTO']],
            'gm_per_meter' => [10.0, 14.0],
            'is_group' => [0, 1],
        ]), $this->niwarCodeId);

        (new BeltRollProductionController())->roll_production_store(new Request([
            'roll_formula_id' => $formula->id,
            'roll_length_mtr' => 50,
            'no_of_rolls' => 1,
        ]));

        $this->assertStringContainsString('no longer balances', session('error'));
        $this->assertEquals(0, BeltRollProduction::where('roll_formula_id', $formula->id)->count());
    }

    // ------------------------------------------------------- roll production

    /** @test */
    public function a_fifty_meter_batch_consumes_the_quantities_the_spec_expects()
    {
        $formula = $this->createFormula();
        $this->stockEverything();

        $before = [
            'mono' => $this->stockOf('XYZ MONO'),
            'black' => $this->stockOf('BLACK THREAD'),
            'white' => $this->stockOf('WHITE THREAD'),
            'red' => $this->stockOf('RED THREAD'),
            'yellow' => $this->stockOf('YELLOW THREAD'),
        ];

        $batch = $this->startBatch($formula, 50, 1);

        $issued = $batch->materials->pluck('required_qty', 'material');

        $this->assertEquals(500.0, $issued[$this->materials['XYZ MONO']], 'Mono = 10 x 50');
        $this->assertEquals(200.0, $issued[$this->materials['BLACK THREAD']], 'Black = 4.0 x 50');
        $this->assertEquals(175.0, $issued[$this->materials['WHITE THREAD']], 'White = 3.5 x 50');
        $this->assertEquals(130.0, $issued[$this->materials['RED THREAD']], 'Red = 2.6 x 50');
        $this->assertEquals(100.0, $issued[$this->materials['YELLOW THREAD']], 'Yellow = 2.0 x 50');

        // The Roto threads must still come to the 12.1 x 50 = 605 g total.
        $rotoTotal = $batch->materials
            ->whereIn('material', [
                $this->materials['BLACK THREAD'], $this->materials['WHITE THREAD'],
                $this->materials['RED THREAD'], $this->materials['YELLOW THREAD'],
            ])
            ->sum('required_qty');
        $this->assertEquals(605.0, round($rotoTotal, 2));

        // And every gram of it must have left the shelf.
        $this->assertEquals($before['mono'] - 500, $this->stockOf('XYZ MONO'));
        $this->assertEquals($before['black'] - 200, $this->stockOf('BLACK THREAD'));
        $this->assertEquals($before['white'] - 175, $this->stockOf('WHITE THREAD'));
        $this->assertEquals($before['red'] - 130, $this->stockOf('RED THREAD'));
        $this->assertEquals($before['yellow'] - 100, $this->stockOf('YELLOW THREAD'));
    }

    /** @test */
    public function the_batch_records_the_formula_version_it_was_started_on()
    {
        $formula = $this->createFormula();
        $this->stockEverything();

        $batch = $this->startBatch($formula, 50, 1);
        $this->assertEquals(1, $batch->roll_formula_version);

        // Move the recipe on; the batch must not follow it.
        (new RollFormulaController())->roll_formula_update(new Request($this->formulaPayload([
            'id' => $formula->id,
            'gm_per_meter' => [10.0, 5.0, 2.5, 2.6, 2.0],
        ])));

        $this->assertEquals(2, $formula->fresh()->version);
        $this->assertEquals(1, $batch->fresh()->roll_formula_version);
        $this->assertEquals(200.0, $batch->fresh()->materials->firstWhere('material', $this->materials['BLACK THREAD'])->required_qty);
    }

    /** @test */
    public function insufficient_dhaga_blocks_the_batch_and_moves_no_stock()
    {
        $formula = $this->createFormula();
        $this->giveStock([
            'XYZ MONO' => 100, // needs 500
            'BLACK THREAD' => 100000, 'WHITE THREAD' => 100000,
            'RED THREAD' => 100000, 'YELLOW THREAD' => 100000,
        ]);

        $response = (new BeltRollProductionController())->roll_production_store(new Request([
            'roll_formula_id' => $formula->id,
            'roll_length_mtr' => 50,
            'no_of_rolls' => 1,
        ]));

        $this->assertStringContainsString('Insufficient raw material', session('error'));
        $this->assertEquals(0, BeltRollProduction::where('roll_formula_id', $formula->id)->count());
        // Nothing may be taken off the shelf by a batch that was never created.
        $this->assertEquals(100.0, $this->stockOf('XYZ MONO'));
        $this->assertEquals(100000.0, $this->stockOf('BLACK THREAD'));
    }

    /** @test */
    public function a_shortage_can_be_sent_to_the_purchase_department_instead()
    {
        $formula = $this->createFormula();
        $this->giveStock([
            'XYZ MONO' => 100, // needs 500, so 400 g short
            'BLACK THREAD' => 100000, 'WHITE THREAD' => 100000,
            'RED THREAD' => 100000, 'YELLOW THREAD' => 100000,
        ]);

        (new BeltRollProductionController())->roll_production_store(new Request([
            'roll_formula_id' => $formula->id,
            'roll_length_mtr' => 50,
            'no_of_rolls' => 1,
            'action' => 'purchase_request',
        ]));

        $requirement = DB::table('purchase_requirement')->orderByDesc('id')->first();
        $this->assertEquals('belt', $requirement->module);

        $lines = DB::table('purchase_required_material')->where('order_id', $requirement->id)->get();
        $this->assertCount(1, $lines, 'Only the short material belongs on the request.');
        $this->assertEquals($this->materials['XYZ MONO'], $lines->first()->raw_material);
        $this->assertEquals(400.0, (float) $lines->first()->qty);

        // Raising a requirement is not production - no dhaga may move.
        $this->assertEquals(100.0, $this->stockOf('XYZ MONO'));
        $this->assertEquals(0, BeltRollProduction::where('roll_formula_id', $formula->id)->count());
    }

    /**
     * The purchase department sees socks and belt requirements in one list, so a
     * belt one has to be tellable apart - and filterable.
     *
     * @test
     */
    public function a_belt_purchase_request_is_marked_belt_and_filterable_as_such()
    {
        $formula = $this->createFormula();
        $this->giveStock([
            'XYZ MONO' => 100,
            'BLACK THREAD' => 100000, 'WHITE THREAD' => 100000,
            'RED THREAD' => 100000, 'YELLOW THREAD' => 100000,
        ]);

        (new BeltRollProductionController())->roll_production_store(new Request([
            'roll_formula_id' => $formula->id,
            'roll_length_mtr' => 50,
            'no_of_rolls' => 1,
            'action' => 'purchase_request',
        ]));

        $requirement = DB::table('purchase_requirement')->orderByDesc('id')->first();
        $this->assertEquals('belt', $requirement->module);

        // The list filtered to belt must include it...
        $belt = (new \App\Http\Controllers\PurchaseController())
            ->requirement_list(new Request(['module' => 'belt']))
            ->getData()['list'];
        $this->assertTrue($belt->contains('id', $requirement->id));

        // ...and filtered to socks must not.
        $socks = (new \App\Http\Controllers\PurchaseController())
            ->requirement_list(new Request(['module' => 'socks']))
            ->getData()['list'];
        $this->assertFalse($socks->contains('id', $requirement->id));
    }

    /** @test */
    public function a_formula_that_no_longer_matches_its_niwar_rate_cannot_issue_dhaga()
    {
        $formula = $this->createFormula();
        $this->stockEverything();

        // The niwar code is re-rated after the formula was saved, so the recipe's
        // 12.1 g of Roto threads no longer adds up to the category total.
        DB::table('niwar_type_materials')->where('id', $this->rotoCategoryId)->update(['gm_per_meter' => 14.0]);

        (new BeltRollProductionController())->roll_production_store(new Request([
            'roll_formula_id' => $formula->id,
            'roll_length_mtr' => 50,
            'no_of_rolls' => 1,
        ]));

        $this->assertStringContainsString('no longer balances', session('error'));
        $this->assertEquals(0, BeltRollProduction::where('roll_formula_id', $formula->id)->count());
        $this->assertEquals(100000.0, $this->stockOf('XYZ MONO'));
    }

    /** @test */
    public function an_inactive_formula_cannot_start_a_new_batch()
    {
        $formula = $this->createFormula();
        $this->stockEverything();
        $formula->status = 'inactive';
        $formula->save();

        (new BeltRollProductionController())->roll_production_store(new Request([
            'roll_formula_id' => $formula->id,
            'roll_length_mtr' => 50,
            'no_of_rolls' => 1,
        ]));

        $this->assertStringContainsString('inactive', session('error'));
        $this->assertEquals(0, BeltRollProduction::where('roll_formula_id', $formula->id)->count());
    }

    /** @test */
    public function completing_the_batch_creates_the_rolls_and_stocks_the_meters()
    {
        $formula = $this->createFormula();
        $this->stockEverything();
        $batch = $this->startBatch($formula, 50, 1);

        (new BeltRollProductionController())->roll_production_complete(new Request([
            'id' => $batch->id,
            'produced_mtr' => 50,
        ]));

        $batch->refresh();
        $this->assertEquals('Y', $batch->status);
        $this->assertEquals(0, $batch->wastage_mtr);
        $this->assertNotNull($batch->completed_at);

        $rolls = BeltRoll::where('belt_roll_production_id', $batch->id)->get();
        $this->assertCount(1, $rolls);
        $this->assertEquals(50.0, $rolls->first()->length_mtr);
        $this->assertEquals(50.0, $rolls->first()->remaining_mtr);
        $this->assertEquals(50.0, $this->stockOf($batch->roll_product_id));
    }

    /** @test */
    public function a_short_run_splits_into_full_rolls_plus_a_remainder()
    {
        $formula = $this->createFormula();
        $this->stockEverything();
        $batch = $this->startBatch($formula, 70, 5); // 350 mtr planned

        (new BeltRollProductionController())->roll_production_complete(new Request([
            'id' => $batch->id,
            'produced_mtr' => 340,
        ]));

        $lengths = BeltRoll::where('belt_roll_production_id', $batch->id)->pluck('length_mtr')->map(fn ($l) => (float) $l)->all();

        $this->assertEquals([70.0, 70.0, 70.0, 70.0, 60.0], $lengths);
        $this->assertEquals(10.0, $batch->fresh()->wastage_mtr);
    }

    /** @test */
    public function producing_more_than_planned_is_refused_and_moves_nothing()
    {
        $formula = $this->createFormula();
        $this->stockEverything();
        $batch = $this->startBatch($formula, 50, 1);
        $afterIssue = $this->stockOf('XYZ MONO');

        (new BeltRollProductionController())->roll_production_complete(new Request([
            'id' => $batch->id,
            'produced_mtr' => 55, // dhaga was only issued for 50 mtr
        ]));

        $this->assertStringContainsString('cannot be more than the planned', session('error'));

        $batch->refresh();
        $this->assertEquals('N', $batch->status, 'A refused completion must leave the batch open.');
        $this->assertNull($batch->produced_mtr);
        $this->assertEquals(0, BeltRoll::where('belt_roll_production_id', $batch->id)->count());
        $this->assertEquals(0.0, $this->stockOf($batch->roll_product_id));
        $this->assertEquals($afterIssue, $this->stockOf('XYZ MONO'), 'The rolled-back attempt must not touch dhaga.');
    }

    /** @test */
    public function producing_exactly_the_planned_meters_is_allowed()
    {
        $formula = $this->createFormula();
        $this->stockEverything();
        // 3 x 16.67 = 50.01 planned, the kind of figure float drift bites on.
        $batch = $this->startBatch($formula, 16.67, 3);

        (new BeltRollProductionController())->roll_production_complete(new Request([
            'id' => $batch->id,
            'produced_mtr' => $batch->planned_mtr,
        ]));

        $this->assertEquals('Y', $batch->fresh()->status, session('error') ?? '');
        $this->assertEquals(0.0, (float) $batch->fresh()->wastage_mtr);
    }

    /** @test */
    public function actual_consumption_posts_only_the_difference_to_stock()
    {
        $formula = $this->createFormula();
        $this->stockEverything();
        $batch = $this->startBatch($formula, 50, 1);

        $monoRow = $batch->materials->firstWhere('material', $this->materials['XYZ MONO']);
        $blackRow = $batch->materials->firstWhere('material', $this->materials['BLACK THREAD']);
        $afterIssue = $this->stockOf('XYZ MONO');

        (new BeltRollProductionController())->roll_production_complete(new Request([
            'id' => $batch->id,
            'produced_mtr' => 50,
            'actual_qty' => [
                $monoRow->id => 510,  // 10 g over plan
                $blackRow->id => 190, // 10 g under plan
            ],
        ]));

        // Only the variance moves - the planned quantity was already issued.
        $this->assertEquals($afterIssue - 10, $this->stockOf('XYZ MONO'));
        $this->assertEquals(100000 - 200 + 10, $this->stockOf('BLACK THREAD'));

        $this->assertEquals(510.0, $monoRow->fresh()->actual_qty);
        $this->assertEquals(190.0, $blackRow->fresh()->actual_qty);
        // Untouched rows default to the plan rather than staying unrecorded.
        $this->assertEquals(175.0, $batch->fresh()->materials->firstWhere('material', $this->materials['WHITE THREAD'])->actual_qty);
    }

    /** @test */
    public function a_batch_cannot_be_completed_twice()
    {
        $formula = $this->createFormula();
        $this->stockEverything();
        $batch = $this->startBatch($formula, 50, 1);

        $complete = fn () => (new BeltRollProductionController())->roll_production_complete(new Request([
            'id' => $batch->id, 'produced_mtr' => 50,
        ]));

        $complete();
        $complete();

        $this->assertStringContainsString('already completed', session('error'));
        $this->assertEquals(1, BeltRoll::where('belt_roll_production_id', $batch->id)->count());
        $this->assertEquals(50.0, $this->stockOf($batch->roll_product_id), 'The second attempt must not stock the meters again.');
    }

    /** @test */
    public function cancelling_a_batch_returns_every_gram_and_voids_its_rolls()
    {
        $formula = $this->createFormula();
        $this->stockEverything();
        $batch = $this->startBatch($formula, 50, 1);

        (new BeltRollProductionController())->roll_production_complete(new Request([
            'id' => $batch->id, 'produced_mtr' => 50,
        ]));

        (new BeltRollProductionController())->roll_production_cancel(new Request([
            'id' => $batch->id, 'cancel_reason' => 'Loom fault',
        ]));

        $this->assertEquals('C', $batch->fresh()->status);
        $this->assertEquals(100000.0, $this->stockOf('XYZ MONO'), 'Dhaga must be back exactly where it started.');
        $this->assertEquals(100000.0, $this->stockOf('BLACK THREAD'));
        $this->assertEquals(0.0, $this->stockOf($batch->roll_product_id), 'The roll meters must come back out of stock.');
        $this->assertEquals('cancelled', BeltRoll::where('belt_roll_production_id', $batch->id)->first()->status);
    }

    /** @test */
    public function a_batch_whose_roll_has_been_cut_cannot_be_cancelled()
    {
        [$batch, $roll] = $this->batchWithRoll();
        $this->cut($roll, ['30' => 5]);

        (new BeltRollProductionController())->roll_production_cancel(new Request([
            'id' => $batch->id, 'cancel_reason' => 'Too late',
        ]));

        $this->assertStringContainsString('already been cut', session('error'));
        $this->assertEquals('Y', $batch->fresh()->status);
    }

    // -------------------------------------------------------------- cutting


    /** @test */
    public function the_screen_reports_the_whole_size_chart_and_which_sizes_have_no_product()
    {
        [, $roll] = $this->batchWithRoll();

        // A charted size no finished belt exists in, so it cannot be cut.
        DB::table('niwar_size_charts')->insert([
            'niwar_code_id' => $this->niwarCodeId,
            'pp_size' => 'TESTSIZE',
            'required_inch' => 33.0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $chart = (new BeltCuttingController())
            ->belt_cutting_products(new Request(['roll_id' => $roll->id]))
            ->getData(true)['size_chart'];

        $this->assertContains('TESTSIZE', collect($chart['charted'])->pluck('size')->all());
        $this->assertContains('TESTSIZE', $chart['missing'], 'A charted size with no belt product must be reported as missing.');
        $this->assertNotContains('TESTSIZE', collect($chart['cuttable'])->pluck('size')->all());
    }




    /**
     * The cutting screen has to answer, per size: how much roll in inches, how
     * many meters that is, and what those meters weigh in dhaga.
     *
     * @test
     */
    public function each_size_reports_its_inch_meter_and_gram_usage()
    {
        [, $roll] = $this->batchWithRoll();
        $this->beltProductFor('30');

        $payload = (new BeltCuttingController())
            ->belt_cutting_products(new Request(['roll_id' => $roll->id]))
            ->getData(true);

        $row = collect($payload['products'])->firstWhere('size', '30');

        // Chart: size 30 = 32 inch. Niwar weighs Mono 10 + Roto 12.1 = 22.1 g/mtr.
        $meters = round(32 / 39.37, 4);

        $this->assertEquals(32.0, $row['required_inch']);
        $this->assertEquals($meters, $row['meter_per_piece']);
        $this->assertEquals(round($meters * 22.1, 2), $row['gram_per_piece']);
        $this->assertEquals(22.1, $payload['size_chart']['gm_per_meter']);
    }

    /** @test */
    public function the_size_chart_carries_inch_meter_and_gram_for_every_charted_size()
    {
        [, $roll] = $this->batchWithRoll();
        $this->beltProductFor('30');

        $charted = (new BeltCuttingController())
            ->belt_cutting_products(new Request(['roll_id' => $roll->id]))
            ->getData(true)['size_chart']['charted'];

        foreach ($charted as $row) {
            $this->assertEquals(round($row['inch'] / 39.37, 4), $row['mtr'], 'Meters must be inches over inch_per_meter.');
            $this->assertEquals(round($row['mtr'] * 22.1, 2), $row['gm'], 'Grams must be meters times the niwar rate.');
        }
    }

    /**
     * The dhaga weight of a cut is split the way the niwar code is rated, so Mono
     * and Roto are each accounted for rather than lumped into one number.
     *
     * @test
     */
    public function the_cutting_preview_bifurcates_the_dhaga_weight_by_category()
    {
        [, $roll] = $this->batchWithRoll();
        $beltProduct = $this->beltProductFor('30');

        $controller = new BeltCuttingController();
        $plan = new \ReflectionMethod($controller, 'computeCuttingPlan');
        $plan->setAccessible(true);

        $roll->load('niwar.sizeChart', 'niwar.materials.material_item:id,product_name');
        $result = $plan->invoke($controller, $roll, [$beltProduct], [10], [0], 2.0);

        $perPiece = round(32 / 39.37, 4);
        $cutMeter = round($perPiece * 10, 2);

        $this->assertEquals($cutMeter, $result['cut_meter']);
        $this->assertEquals(2.0, $result['wastage_mtr']);
        $this->assertEquals(round(($cutMeter + 2.0) * 22.1, 2), $result['total_gram']);

        // Every weight is derived from meters, so the parts must add to the whole
        // rather than drifting apart through rounded per-piece figures.
        $this->assertEquals(
            $result['total_gram'],
            round($result['cut_gram'] + $result['wastage_gram'], 2),
            'Belt grams plus trim grams must equal the grams that left the roll.'
        );
        $this->assertEquals(
            $result['cut_gram'],
            round($result['items']->sum('total_gram'), 2),
            'The size rows must add up to the belt total.'
        );

        $mono = $result['dhaga_breakdown']->firstWhere('name', 'TEST XYZ MONO');
        $roto = $result['dhaga_breakdown']->firstWhere('name', 'TEST XYZ ROTO');

        $this->assertEquals(round($cutMeter * 10.0, 2), $mono->gram_in_belts);
        $this->assertEquals(round($cutMeter * 12.1, 2), $roto->gram_in_belts);
        $this->assertTrue($roto->is_group, 'Roto is a composite category and must say so.');

        // Trim wastage carries its share of dhaga too, and the parts must total.
        $this->assertEquals(round(2.0 * 10.0, 2), $mono->gram_in_wastage);
        $this->assertEquals(
            $result['total_gram'],
            round($result['dhaga_breakdown']->sum('gram_total'), 2),
            'The per-category weights must add up to the total.'
        );
    }

    /**
     * Any finished belt whose size the roll's niwar is charted for is cuttable -
     * no belt formula needed. That is what makes the whole catalogue reachable.
     *
     * @test
     */
    public function any_sized_belt_product_on_the_chart_is_cuttable_without_a_belt_formula()
    {
        [, $roll] = $this->batchWithRoll();
        $beltProduct = $this->beltProductFor('32');

        $this->assertEquals(
            0,
            DB::table('buckle_formula_mst')->where('product', $beltProduct)->count(),
            'This product deliberately has no belt formula.'
        );

        $offered = collect((new BeltCuttingController())
            ->belt_cutting_products(new Request(['roll_id' => $roll->id, 'search' => self::FAMILY]))
            ->getData(true)['products'])->pluck('belt_product')->all();

        $this->assertContains($beltProduct, $offered);
    }

    /**
     * A roll formula names the belt it becomes. The size on that pick is
     * meaningless to a roll - what it buys is the product identity, so cutting
     * offers that one belt's own size range.
     *
     * @test
     */
    public function cutting_offers_only_the_sizes_of_the_belt_the_roll_was_woven_for()
    {
        // Two families, both sized for this niwar's chart.
        $mine30 = $this->namedBeltProduct('TEST FAMILY A', '30');
        $mine32 = $this->namedBeltProduct('TEST FAMILY A', '32');
        $other = $this->namedBeltProduct('TEST FAMILY B', '30');

        $formula = $this->createFormula();
        // Picked at size 30; the size must not narrow anything, the name must.
        $formula->belt_product_id = $mine30;
        $formula->save();

        $this->stockEverything();
        $batch = $this->startBatch($formula, 50, 1);
        (new BeltRollProductionController())->roll_production_complete(new Request(['id' => $batch->id, 'produced_mtr' => 50]));
        $roll = BeltRoll::where('belt_roll_production_id', $batch->id)->first();

        $payload = (new BeltCuttingController())
            ->belt_cutting_products(new Request(['roll_id' => $roll->id]))
            ->getData(true);

        $offered = collect($payload['products'])->pluck('belt_product')->all();

        $this->assertStringContainsString('TEST FAMILY A', $payload['belt_family']);
        $this->assertContains($mine30, $offered);
        $this->assertContains($mine32, $offered, 'Every size of the chosen belt must be offered, not just the one picked.');
        $this->assertNotContains($other, $offered, 'A different belt family must not be offered.');
    }

    /**
     * Two belts can share a name and differ only by item code - BRASS_COTTON and
     * BRASS_MALAI are different products, not sizes of one.
     *
     * @test
     */
    public function belts_sharing_a_name_but_not_the_item_code_are_different_products()
    {
        $cotton = $this->namedBeltProduct('TEST SHARED NAME', '30', 'TST_COTTON');
        $malai = $this->namedBeltProduct('TEST SHARED NAME', '32', 'TST_MALAI');

        $formula = $this->createFormula();
        $formula->belt_product_id = $cotton;
        $formula->save();

        $this->stockEverything();
        $batch = $this->startBatch($formula, 50, 1);
        (new BeltRollProductionController())->roll_production_complete(new Request(['id' => $batch->id, 'produced_mtr' => 50]));
        $roll = BeltRoll::where('belt_roll_production_id', $batch->id)->first();

        $offered = collect((new BeltCuttingController())
            ->belt_cutting_products(new Request(['roll_id' => $roll->id]))
            ->getData(true)['products'])->pluck('belt_product')->all();

        $this->assertContains($cotton, $offered);
        $this->assertNotContains($malai, $offered, 'A different item code is a different belt.');
    }

    /** @test */
    public function a_belt_from_another_family_cannot_be_cut_even_if_posted_directly()
    {
        $mine = $this->namedBeltProduct('TEST FAMILY A', '30');
        $other = $this->namedBeltProduct('TEST FAMILY B', '32');

        $formula = $this->createFormula();
        $formula->belt_product_id = $mine;
        $formula->save();

        $this->stockEverything();
        $batch = $this->startBatch($formula, 50, 1);
        (new BeltRollProductionController())->roll_production_complete(new Request(['id' => $batch->id, 'produced_mtr' => 50]));
        $roll = BeltRoll::where('belt_roll_production_id', $batch->id)->first();

        (new BeltCuttingController())->belt_cutting_store(new Request([
            'roll_id' => $roll->id,
            'belt_product' => [$other],
            'pieces' => [5],
        ]));

        $this->assertStringContainsString('at least one size', session('error'));
        $this->assertEquals(0.0, $this->stockOf($other));
        $this->assertEquals(50.0, round($roll->fresh()->remaining_mtr, 2));
    }

    /** @test */
    public function a_formula_with_no_belt_product_leaves_cutting_open()
    {
        [$batch, $roll] = $this->batchWithRoll();
        $this->beltProductFor('30');

        // Legacy shape: a formula saved before the belt product existed.
        RollFormulaMst::where('id', $batch->roll_formula_id)->update(['belt_product_id' => null]);

        $payload = (new BeltCuttingController())
            ->belt_cutting_products(new Request(['roll_id' => $roll->id]))
            ->getData(true);

        $this->assertNull($payload['belt_family'], 'No belt named means no narrowing.');
        $this->assertGreaterThan(0, $payload['total_products']);
    }

    /** @test */
    public function the_product_search_narrows_the_catalogue()
    {
        [, $roll] = $this->batchWithRoll();
        $this->beltProductFor('30');

        $controller = new BeltCuttingController();

        $hit = collect($controller->belt_cutting_products(new Request(['roll_id' => $roll->id, 'search' => self::FAMILY]))
            ->getData(true)['products'])->pluck('base_name')->unique()->all();
        $miss = collect($controller->belt_cutting_products(new Request(['roll_id' => $roll->id, 'search' => 'ZZZ NOTHING MATCHES ZZZ']))
            ->getData(true)['products'])->all();

        $this->assertEquals([self::FAMILY], $hit);
        $this->assertEmpty($miss);
    }

    /**
     * Bukkal, kadi and panni now come off the niwar's belt costing rather than a
     * per-belt formula, and are consumed for every piece cut - rejects included,
     * since they were fitted before they failed.
     *
     * @test
     */
    public function fitting_is_consumed_from_the_belt_costing()
    {
        [, $roll] = $this->batchWithRoll();
        $bukkal = $this->giveBeltCosting(2);
        DB::table('stock_status')->insert([
            'product' => $bukkal, 'qty' => 500,
            'inward_date' => date('Y-m-d'), 'created_time' => date('d-m-Y h:i:s a'),
        ]);

        $this->cut($roll, ['30' => 10], ['30' => 3]);

        // 10 pieces cut (3 later rejected) x 2 bukkal each = 20 off stock.
        $this->assertEquals(480.0, $this->stockOf($bukkal));

        $cutting = BeltCutting::latest('id')->first();
        $this->assertEquals(20.0, $cutting->materials->firstWhere('material', $bukkal)->required_qty);
    }

    /** @test */
    public function short_fitting_stock_blocks_the_cut_entirely()
    {
        [, $roll] = $this->batchWithRoll();
        $beltProduct = $this->beltProductFor('30');
        $bukkal = $this->giveBeltCosting(1);
        DB::table('stock_status')->insert([
            'product' => $bukkal, 'qty' => 3, // 10 needed
            'inward_date' => date('Y-m-d'), 'created_time' => date('d-m-Y h:i:s a'),
        ]);
        $before = $roll->remaining_mtr;

        $this->cut($roll, ['30' => 10]);

        $this->assertStringContainsString('Insufficient fitting material', session('error'));
        $this->assertEquals(3.0, $this->stockOf($bukkal), 'A blocked cut must not touch fitting stock.');
        $this->assertEquals(0.0, $this->stockOf($beltProduct));
        $this->assertEquals($before, $roll->fresh()->remaining_mtr);
    }

    /** @test */
    public function cancelling_returns_the_fitting_taken_from_the_belt_costing()
    {
        [, $roll] = $this->batchWithRoll();
        $bukkal = $this->giveBeltCosting(1);
        DB::table('stock_status')->insert([
            'product' => $bukkal, 'qty' => 100,
            'inward_date' => date('Y-m-d'), 'created_time' => date('d-m-Y h:i:s a'),
        ]);

        $this->cut($roll, ['30' => 10]);
        $this->assertEquals(90.0, $this->stockOf($bukkal));

        (new BeltCuttingController())->belt_cutting_cancel(new Request([
            'id' => BeltCutting::latest('id')->first()->id,
            'cancel_reason' => 'Wrong roll',
        ]));

        $this->assertEquals(100.0, $this->stockOf($bukkal), 'Reversal must put the bukkal back.');
    }

    /** @test */
    public function a_size_that_is_not_on_the_chart_cannot_be_cut()
    {
        [, $roll] = $this->batchWithRoll();
        // 99 has a belt formula but no row on the niwar size chart.
        $this->beltProductFor('99');

        $products = (new BeltCuttingController())
            ->belt_cutting_products(new Request(['roll_id' => $roll->id]))
            ->getData(true)['products'];

        $this->assertNull(collect($products)->firstWhere('size', '99'));
    }

    /** @test */
    public function cutting_takes_meters_off_the_roll_and_puts_belts_into_stock()
    {
        [$batch, $roll] = $this->batchWithRoll();
        $beltProduct = $this->beltProductFor('30');

        $this->cut($roll, ['30' => 10]);

        // 32 inch / 39.37 = 0.8128 mtr a piece, 10 pieces = 8.13 mtr.
        $perPiece = round(32 / 39.37, 4);
        $used = round($perPiece * 10, 2);

        $roll->refresh();
        $this->assertEquals(round(50 - $used, 2), round($roll->remaining_mtr, 2));
        $this->assertEquals(10.0, $this->stockOf($beltProduct));
        $this->assertEquals(round(50 - $used, 2), round($this->stockOf($batch->roll_product_id), 2));
    }

    /** @test */
    public function rejected_pieces_eat_the_roll_but_never_reach_finished_stock()
    {
        [, $roll] = $this->batchWithRoll();
        $beltProduct = $this->beltProductFor('30');

        $this->cut($roll, ['30' => 10], ['30' => 3]);

        $this->assertEquals(7.0, $this->stockOf($beltProduct), 'Only the good pieces are stocked.');

        $cutting = BeltCutting::latest('id')->first();
        $this->assertEquals(10, $cutting->total_pieces);
        $this->assertEquals(3, $cutting->total_rejected_pieces);
        // All ten pieces were cut, so all ten pieces' worth of niwar is gone.
        $this->assertEquals(round(round(32 / 39.37, 4) * 10, 2), round($cutting->total_meter_used, 2));
    }

    /** @test */
    public function trim_wastage_is_taken_off_the_roll_and_kept_apart_from_the_balance()
    {
        [, $roll] = $this->batchWithRoll();

        $this->cut($roll, ['30' => 10], [], 2.5);

        $cutting = BeltCutting::latest('id')->first();
        $perPiece = round(32 / 39.37, 4);

        $this->assertEquals(2.5, round($cutting->wastage_mtr, 2));
        $this->assertEquals(round($perPiece * 10 + 2.5, 2), round($cutting->total_meter_used, 2));
        $this->assertEquals(round(50 - $perPiece * 10 - 2.5, 2), round($roll->fresh()->remaining_mtr, 2));
    }

    /** @test */
    public function a_roll_cannot_be_cut_beyond_its_remaining_meters()
    {
        [, $roll] = $this->batchWithRoll();
        $beltProduct = $this->beltProductFor('30');
        $before = $roll->remaining_mtr;

        // 100 pieces at 0.8128 mtr is 81 mtr out of a 50 mtr roll.
        $this->cut($roll, ['30' => 100]);

        $this->assertStringContainsString('only has', session('error'));
        $this->assertEquals($before, $roll->fresh()->remaining_mtr);
        $this->assertEquals(0.0, $this->stockOf($beltProduct));
    }

    /** @test */
    public function a_fully_consumed_roll_closes_itself()
    {
        [, $roll] = $this->batchWithRoll();

        // 61 pieces at 0.8128 = 49.58 mtr, then trim the last 0.42.
        $this->cut($roll, ['30' => 61], [], 0.42);

        $roll->refresh();
        $this->assertEquals(0.0, round($roll->remaining_mtr, 2));
        $this->assertEquals('consumed', $roll->status);
    }

    /** @test */
    public function cancelling_a_cutting_puts_the_belts_meters_and_fittings_back()
    {
        [$batch, $roll] = $this->batchWithRoll();
        $beltProduct = $this->beltProductFor('30');

        $this->cut($roll, ['30' => 10]);
        $cutting = BeltCutting::latest('id')->first();

        (new BeltCuttingController())->belt_cutting_cancel(new Request([
            'id' => $cutting->id, 'cancel_reason' => 'Wrong size cut',
        ]));

        $this->assertEquals('C', $cutting->fresh()->status);
        $this->assertEquals(0.0, $this->stockOf($beltProduct), 'The belts must come back out of finished stock.');
        $this->assertEquals(50.0, round($roll->fresh()->remaining_mtr, 2), 'The meters must go back onto the roll.');
        $this->assertEquals(50.0, round($this->stockOf($batch->roll_product_id), 2));
    }

    /** @test */
    public function closing_a_roll_writes_its_tail_off_as_scrap()
    {
        [$batch, $roll] = $this->batchWithRoll();

        $this->cut($roll, ['30' => 10]);
        $remaining = round($roll->fresh()->remaining_mtr, 2);

        (new BeltCuttingController())->belt_cutting_close_roll(new Request(['roll_id' => $roll->id]));

        $roll->refresh();
        $this->assertEquals('scrap', $roll->status);
        $this->assertEquals(0.0, (float) $roll->remaining_mtr);
        $this->assertEquals($remaining, round($roll->scrap_mtr, 2));
        $this->assertEquals(0.0, round($this->stockOf($batch->roll_product_id), 2));
    }

    /** @test */
    public function a_closed_roll_cannot_be_cut_again()
    {
        [, $roll] = $this->batchWithRoll();
        (new BeltCuttingController())->belt_cutting_close_roll(new Request(['roll_id' => $roll->id]));

        $this->cut($roll, ['30' => 1]);

        $this->assertStringContainsString('pick an open roll', session('error'));
    }

    // -------------------------------------------------------- transactions

    /** @test */
    public function stock_helpers_refuse_to_run_outside_a_transaction()
    {
        $controller = new class extends \App\Http\Controllers\Controller {
            use \App\Http\Controllers\Concerns\BeltStockMovement;

            public function move($product)
            {
                return $this->stockOut($product, 1, 'should never happen');
            }
        };

        // DatabaseTransactions holds one open, so drop to zero for this check.
        DB::rollBack();
        try {
            $this->expectException(\LogicException::class);
            $controller->move($this->materials['XYZ MONO']);
        } finally {
            DB::beginTransaction();
        }
    }

    /** @test */
    public function document_numbers_are_unique_per_document_not_per_row_id()
    {
        $formula = $this->createFormula();
        $this->stockEverything();

        $first = $this->startBatch($formula, 50, 1);
        $second = $this->startBatch($formula, 50, 1);

        $this->assertNotEquals($first->batch_no, $second->batch_no);
        $this->assertStringStartsWith('ROLL-', $second->batch_no);
    }

    // ---------------------------------------------------------------- helpers

    private function startBatch(RollFormulaMst $formula, $length, $rolls): BeltRollProduction
    {
        (new BeltRollProductionController())->roll_production_store(new Request([
            'roll_formula_id' => $formula->id,
            'roll_length_mtr' => $length,
            'no_of_rolls' => $rolls,
        ]));

        $batch = BeltRollProduction::with('materials')->latest('id')->first();
        $this->assertNotNull($batch, 'Batch was not created: ' . session('error'));

        return $batch;
    }

    /** A completed 50 mtr batch and its single roll, ready to cut. */
    private function batchWithRoll(): array
    {
        $formula = $this->createFormula();
        $this->stockEverything();
        $batch = $this->startBatch($formula, 50, 1);

        (new BeltRollProductionController())->roll_production_complete(new Request([
            'id' => $batch->id, 'produced_mtr' => 50,
        ]));

        return [$batch->fresh(), BeltRoll::where('belt_roll_production_id', $batch->id)->first()];
    }

    /**
     * A size-wise belt product with a buckle formula costed against this test's
     * niwar code - the link BeltCuttingController uses to decide what a roll can
     * be cut into. Given no fitting material, so cutting tests are about niwar.
     */
    private function beltProductFor($size, $variantSize = null)
    {
        if (isset($this->beltProducts[$size])) {
            return $this->beltProducts[$size];
        }

        return $this->beltProducts[$size] = $this->namedBeltProduct(self::FAMILY, $variantSize ?? $size);
    }

    /**
     * A sized belt under a named family. The item code is constant across a
     * family's sizes - that is what real data does, and together with the name it
     * is what identifies the family.
     */
    private function namedBeltProduct($name, $size, $itemCode = null)
    {
        return DB::table('product')->insertGetId([
            'product_name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)) . '-' . $size,
            'item_code' => $itemCode ?? strtoupper(str_replace(' ', '', $name)),
            'value2' => $size,
            'category' => $this->beltCategoryId(),
            'status' => 'product',
            'website_id' => 1,
            'created_time' => date('d-m-Y h:i:s a'),
        ]);
    }

    private function beltCategoryId()
    {
        return DB::table('category')->where('category_name', 'Belt')->value('id');
    }

    /**
     * The niwar's belt costing, naming the bukkal it consumes per belt. Cutting
     * takes its fitting from here now, not from a belt formula.
     */
    private function giveBeltCosting($bukkalQty = 1)
    {
        $bukkalCodeId = DB::table('bukkal_codes')->insertGetId([
            'type' => 'TESTBK',
            'code' => 'T99',
            'rate' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $bukkalProduct = DB::table('product')->insertGetId([
            'product_name' => 'TEST BUKKAL',
            'slug' => 'test-bukkal',
            'item_code' => 'TST-BUKKAL',
            'status' => 'raw material',
            'website_id' => 1,
            'created_time' => date('d-m-Y h:i:s a'),
        ]);

        $costingId = DB::table('belt_costings')->insertGetId([
            'bukkal_id' => $bukkalCodeId,
            'niwar_id' => $this->niwarCodeId,
            'kadi_qty' => 0,
            'panni_packing' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('belt_costing_fitting')->insert([
            'belt_costing_id' => $costingId,
            'label' => 'Bukkal',
            'product' => $bukkalProduct,
            'qty' => $bukkalQty,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $bukkalProduct;
    }

    /**
     * @param  array  $piecesBySize   e.g. ['30' => 10]
     * @param  array  $rejectsBySize  e.g. ['30' => 3]
     */
    private function cut(BeltRoll $roll, array $piecesBySize, array $rejectsBySize = [], $wastage = 0)
    {
        $products = [];
        $pieces = [];
        $rejects = [];

        foreach ($piecesBySize as $size => $qty) {
            $products[] = $this->beltProductFor($size);
            $pieces[] = $qty;
            $rejects[] = $rejectsBySize[$size] ?? 0;
        }

        return (new BeltCuttingController())->belt_cutting_store(new Request([
            'roll_id' => $roll->id,
            'belt_product' => $products,
            'pieces' => $pieces,
            'rejected_pieces' => $rejects,
            'wastage_mtr' => $wastage,
        ]));
    }
}
