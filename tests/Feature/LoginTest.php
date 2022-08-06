<?php


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use Illuminate\Foundation\Testing\WithoutMiddleware;

uses(RefreshDatabase::class);


it('redirects authenticated user', function () {

    actingAs( User::factory()->create())
        ->get('auth/login')
        ->assertStatus(302);
});

it('shows an  errors if the details are not provided')
    ->post('/login')
    ->assertSessionHasErrors(['email', 'password']);


it('logs the user in', function () {
    $user = User::factory()->create([
        'password' => bcrypt('meowimacat')
    ]);


    post('/login', [
        'email' => $user->email,
        'password' => 'meowimacat'
    ])
        ->assertRedirect('/');

    $this->assertAuthenticated();
});







