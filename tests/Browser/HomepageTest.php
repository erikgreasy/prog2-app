<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomepageTest extends DuskTestCase
{
    public function testHomepage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->with('h1', function ($heading) {
                    $heading->assertSee('Programovanie 2');
                })
                ->click('a[href="/zadania"]')
                ->assertPathIs('/zadania');

        });
    }
}
