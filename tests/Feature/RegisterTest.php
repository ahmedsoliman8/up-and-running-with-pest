<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\post;


uses(RefreshDatabase::class);


it('redirects authenticated user', function () {


    expect(User::factory()->create())->toBeRedirectedFor('/auth/register');
});


it('shows the register page')->get('/auth/register')->assertStatus(200);
it('has errors if the details are not provided')->post('/register')
    ->assertSessionHasErrors(['name', 'email', 'password']);


it('registers the user')
    ->tap(fn() => $this->post('/register', [
        'name' => 'Ahmed',
        'email' => 'ahmed@gmail.com',
        'password' => 'ahmedmahmoud',
        'password_confirmation' => 'ahmedmahmoud'
    ])
        ->assertRedirect('/')
    )
    ->assertDatabaseHas('users', [

        'email' => 'ahmed@gmail.com'
    ])
    ->assertAuthenticated();

