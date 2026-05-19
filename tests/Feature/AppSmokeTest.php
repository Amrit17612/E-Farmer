<?php

namespace Tests\Feature;

use App\Models\Crop;
use App\Models\MarketPrice;
use App\Models\Order;
use App\Models\Scheme;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_load(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
    }

    public function test_main_authenticated_pages_load_with_seeded_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::where('email', 'admin@efarmar.com')->firstOrFail();
        $buyer = User::where('email', 'buyer@efarmar.com')->firstOrFail();
        $admin = User::where('email', 'superadmin@efarmar.com')->firstOrFail();
        $crop = Crop::firstOrFail();
        $order = Order::firstOrFail();
        $scheme = Scheme::firstOrFail();

        $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->actingAs($user)->get('/crops')->assertOk();
        $this->actingAs($user)->get("/crops/{$crop->id}/edit")->assertOk();
        $this->actingAs($user)->get('/market')->assertOk();
        $this->actingAs($user)->get('/sell')->assertOk();
        $this->actingAs($user)->get('/orders')->assertOk();
        $this->actingAs($user)->get("/orders/{$order->id}")->assertOk();
        $this->actingAs($user)->get('/schemes')->assertOk();
        $this->actingAs($user)->get("/schemes/{$scheme->id}")->assertOk();
        $this->actingAs($user)->get('/weather')->assertOk();
        $this->actingAs($user)->get('/profile')->assertOk();

        $this->actingAs($buyer)->get('/dashboard')->assertOk();
        $this->actingAs($buyer)->get('/orders')->assertOk();
        $this->actingAs($buyer)->get('/sell')->assertOk();
        $this->actingAs($buyer)->get("/orders/create?crop_id={$crop->id}")->assertOk();

        $this->actingAs($admin)->get('/dashboard')->assertOk();
        $this->actingAs($admin)->get('/orders')->assertOk();
        $this->actingAs($admin)->get('/crops')->assertOk();

        $this->assertNotNull(MarketPrice::first());
    }
}
