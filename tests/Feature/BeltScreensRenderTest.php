<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * The belt screens do their real work in JavaScript - the roll formula screen
 * builds its category tables from an AJAX call, the cutting screen builds its
 * size rows the same way. That JS is useless if it runs before jQuery is on the
 * page, and every one of these screens used to ship its own second copy of
 * jQuery to work around the ordering. A second copy replaces window.jQuery, so
 * select2 (loaded by the layout, bound to the first copy) ends up triggering
 * change events into an event system nothing is listening on - the dropdown
 * moves and the page does nothing.
 *
 * These tests assert the ordering in the delivered HTML: exactly one jQuery, and
 * the page's own script after both jQuery and select2.
 */
class BeltScreensRenderTest extends TestCase
{
    use DatabaseTransactions;

    private function admin(): User
    {
        $user = User::whereHas('roles', fn ($q) => $q->where('name', 'Super Admin'))->first()
            ?: User::first();

        $this->assertNotNull($user, 'A user is needed to render the admin layout.');

        return $user;
    }

    private function getScreen(string $url): string
    {
        $response = $this->actingAs($this->admin())
            ->withSession(['user_id' => $this->admin()->id, 'software_title' => 'Bestow'])
            ->get($url);

        $response->assertStatus(200);

        return $response->getContent();
    }

    /**
     * @dataProvider screens
     * @test
     */
    public function belt_screens_load_jquery_once_and_run_their_script_after_it($url, $marker)
    {
        $html = $this->getScreen($url);

        $this->assertEquals(
            1,
            substr_count($html, 'js/jquery.min.js'),
            $url . ' must load jQuery exactly once - a second copy orphans select2 event handling.'
        );

        $jquery = strpos($html, 'js/jquery.min.js');
        $select2 = strpos($html, 'select2.min.js');
        $script = strpos($html, $marker);

        $this->assertNotFalse($script, $url . ' did not render its page script.');
        $this->assertGreaterThan($jquery, $script, $url . ' script runs before jQuery is loaded.');
        $this->assertGreaterThan($select2, $script, $url . ' script runs before select2 is loaded.');
    }

    /**
     * Screen URL => a string that appears only in that screen's own script.
     *
     * Function *declarations* rather than names: a bare name also matches the
     * onclick attributes in the table rows, which sit up in the content section
     * and would make the ordering assertion pass or fail on whether the table
     * happened to have rows.
     */
    public function screens(): array
    {
        return [
            'roll formula add' => ['/admin/roll-formula/add', 'categoriesUrl'],
            'roll production add' => ['/admin/roll-production/add', 'checkUrl'],
            'roll production list' => ['/admin/roll-production/list', 'function openComplete'],
            'roll register' => ['/admin/roll-production/register', 'function openClose'],
            'belt cutting add' => ['/admin/belt-cutting/add', 'productsUrl'],
            'belt cutting list' => ['/admin/belt-cutting/list', 'function openDetail'],
        ];
    }

    /**
     * The formula screen is the one that broke: selecting a niwar code has to
     * fetch its categories, so the handler and the endpoint both have to be
     * present in the delivered page.
     *
     * @test
     */
    public function the_roll_formula_screen_wires_the_niwar_code_dropdown()
    {
        $html = $this->getScreen('/admin/roll-formula/add');

        $this->assertStringContainsString('roll-formula/categories', $html, 'The categories endpoint must be in the page.');
        $this->assertStringContainsString("\$('#niwar_code_id').on('change'", $html, 'The niwar dropdown must be wired.');
        $this->assertStringContainsString('categories-container', $html);
    }

    /**
     * Belt Costing now carries the fitting bill that cutting consumes, so its
     * create and edit screens must actually render the three product pickers.
     *
     * @test
     */
    public function belt_costing_screens_offer_the_fitting_products()
    {
        $html = $this->getScreen('/admin/belt-costing/add');

        foreach (['fitting_label[]', 'fitting_product[]', 'fitting_qty[]'] as $field) {
            $this->assertStringContainsString('name="' . $field . '"', $html, $field . ' must be on the costing form.');
        }

        $costingId = \Illuminate\Support\Facades\DB::table('belt_costings')->value('id');
        if ($costingId) {
            $this->assertStringContainsString('name="fitting_product[]"', $this->getScreen('/admin/belt-costing/edit/' . $costingId));
        }
    }

    /**
     * Belt Formula Master is retired: its screens still answer so nothing 404s
     * mid-session, but neither menu should point at it any more.
     *
     * @test
     */
    public function the_retired_belt_formula_master_is_gone_from_the_menu()
    {
        $html = $this->getScreen('/admin/belt-cutting/list');

        $this->assertStringNotContainsString('buckle_formula', $html, 'Belt Formula Master must not be linked from the menu.');
        // The replacements it hands over to are still there.
        $this->assertStringContainsString('roll-formula', $html);
        $this->assertStringContainsString('belt-costing', $html);
    }

    /**
     * The purchase-order screen is reached by posting a requirement, but a
     * refresh or a stale link arrives as a GET with nothing attached. That used
     * to be a MethodNotAllowed page, and with an id missing it fell over on a
     * null further down. Both now land back on the list with a message.
     *
     * @test
     */
    public function opening_the_requirement_purchase_screen_without_a_requirement_redirects()
    {
        $response = $this->actingAs($this->admin())
            ->withSession(['user_id' => $this->admin()->id, 'software_title' => 'Bestow'])
            ->get('/admin/requirement/add');

        $response->assertRedirect(route('admin.requirement.list'));
        $response->assertSessionHas('error');
    }

    /** @test */
    public function opening_it_without_a_vendor_goes_back_to_the_requirement()
    {
        $requirement = \Illuminate\Support\Facades\DB::table('purchase_requirement')->orderByDesc('id')->first();

        if (empty($requirement)) {
            $this->markTestSkipped('No purchase requirement in this database.');
        }

        $response = $this->actingAs($this->admin())
            ->withSession(['user_id' => $this->admin()->id, 'software_title' => 'Bestow'])
            ->get('/admin/requirement/add?id=' . $requirement->id);

        $response->assertRedirect(route('admin.requirement.view', ['id' => $requirement->id]));
        $response->assertSessionHas('error');
    }

    /**
     * The purchase order form posts to po_save, which sends the user back() when
     * its validation fails - and back() is a GET to whatever URL rendered the
     * form. So the form has to be rendered from a GET that carries the
     * requirement and vendor, or a rejected purchase order lands nowhere.
     *
     * @test
     */
    public function posting_a_requirement_redirects_to_a_get_url_that_can_be_returned_to()
    {
        $requirement = \Illuminate\Support\Facades\DB::table('purchase_requirement')->orderByDesc('id')->first();
        $vendor = \Illuminate\Support\Facades\DB::table('vendor')->value('id');

        if (empty($requirement) || empty($vendor)) {
            $this->markTestSkipped('Needs a purchase requirement and a vendor.');
        }

        $response = $this->actingAs($this->admin())
            ->withSession(['user_id' => $this->admin()->id, 'software_title' => 'Bestow'])
            ->post('/admin/requirement/add', ['id' => $requirement->id, 'vendor' => $vendor]);

        $response->assertRedirect(route('admin.requirement.add', [
            'id' => $requirement->id,
            'vendor' => $vendor,
        ]));

        // And that URL renders, so back() from a failed save has somewhere real.
        $this->actingAs($this->admin())
            ->withSession(['user_id' => $this->admin()->id, 'software_title' => 'Bestow'])
            ->get($response->headers->get('Location'))
            ->assertStatus(200);
    }

    /**
     * po_save stamps its PO number onto the requirement via order_id. The field
     * was never rendered, so requirements raised into a purchase order stayed
     * open and could be bought twice.
     *
     * @test
     */
    public function the_purchase_order_form_carries_the_requirement_it_came_from()
    {
        $requirement = \Illuminate\Support\Facades\DB::table('purchase_requirement')->orderByDesc('id')->first();
        $vendor = \Illuminate\Support\Facades\DB::table('vendor')->value('id');

        if (empty($requirement) || empty($vendor)) {
            $this->markTestSkipped('Needs a purchase requirement and a vendor.');
        }

        $html = $this->getScreen('/admin/requirement/add?id=' . $requirement->id . '&vendor=' . $vendor);

        $this->assertStringContainsString(
            'name="order_id" value="' . $requirement->id . '"',
            $html,
            'The purchase order must carry the requirement id so po_save can close it.'
        );
    }

    /** @test */
    public function opening_it_with_a_requirement_and_vendor_renders()
    {
        $requirement = \Illuminate\Support\Facades\DB::table('purchase_requirement')->orderByDesc('id')->first();
        $vendor = \Illuminate\Support\Facades\DB::table('vendor')->value('id');

        if (empty($requirement) || empty($vendor)) {
            $this->markTestSkipped('Needs a purchase requirement and a vendor.');
        }

        $this->actingAs($this->admin())
            ->withSession(['user_id' => $this->admin()->id, 'software_title' => 'Bestow'])
            ->get('/admin/requirement/add?id=' . $requirement->id . '&vendor=' . $vendor)
            ->assertStatus(200);
    }

    /** @test */
    public function the_new_belt_reports_render()
    {
        foreach ([
            '/admin/reports/roll-production',
            '/admin/reports/roll-material-consumption',
            '/admin/reports/belt-cutting',
        ] as $url) {
            $this->getScreen($url);
        }
    }
}
