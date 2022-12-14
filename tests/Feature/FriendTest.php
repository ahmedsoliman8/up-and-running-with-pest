<?php

use App\Models\Book;
use App\Models\Pivot\BookUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);


it('redirects unauthenicated users')
    ->expectGuest()->toBeRedirectedFor('/friends');


it('shows a list of the users pending friends', function () {

    $user = User::factory()->create();

    $friends = User::factory()->times(2)->create();

    $friends->each(fn($friend) => $user->addFriend($friend));


    actingAs($user)
        ->get('/friends')
        ->assertOk()
        ->assertSeeTextInOrder(array_merge(['Pending friend request'], $friends->pluck('name')->toArray()));

});


it('shows a list of the users friend request', function () {

    $user = User::factory()->create();

    $friends = User::factory()->times(2)->create();

    $friends->each(fn($friend) => $friend->addFriend($user));


    actingAs($user)
        ->get('/friends')
        ->assertOk()
        ->assertSeeTextInOrder(array_merge(['Friend requests'], $friends->pluck('name')->toArray()));

});


it('shows a list of  users accepted friends', function () {

    $user = User::factory()->create();

    $friends = User::factory()->times(2)->create();

    $friends->each(function ($friend) use ($user) {
        $user->addFriend($friend);
        $friend->acceptFriend($user);
    });


    actingAs($user)
        ->get('/friends')
        ->assertOk()
        ->assertSeeTextInOrder(array_merge(['Friends'], $friends->pluck('name')->toArray()));

});


it('can get books of friends', function () {

    $user = User::factory()->create();

    $friendOne = User::factory()->create();
    $friendTwo = User::factory()->create();

    $friendThree = User::factory()->create();


    $friendOne->books()->attach($bookOne = Book::factory()->create(), [
        'status' => 'WANT_TO_READ',
        'updated_at' => now()
    ]);


    $friendTwo->books()->attach($bookTwo = Book::factory()->create(), [
        'status' => 'WANT_TO_READ',
        'updated_at' => now()->addDay()
    ]);

    $friendThree->books()->attach($bookThree = Book::factory()->create(), [
        'status' => 'WANT_TO_READ'
    ]);


    $user->addFriend($friendOne);

    $friendOne->acceptFriend($user);


    $friendTwo->addFriend($user);

    $user->acceptFriend($friendTwo);


    $user->addFriend($friendThree);


    expect($user->booksOfFriends)
        ->count()->toBe(2)
        ->first()->title->toBe($bookTwo->title);


});














