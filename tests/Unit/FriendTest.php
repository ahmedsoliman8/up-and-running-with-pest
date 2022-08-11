<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;
use function Pest\Laravel\post;


uses(RefreshDatabase::class);


it('can have pending friends', function () {

    $user = User::factory()->create();
    $friend = User::factory()->create();
    $user->addFriend($friend);
    expect($user->pendingFriendsOfMine)->toHaveCount(1);
});


it('can have friend requests', function () {

    $user = User::factory()->create();
    $friend = User::factory()->create();
    $friend->addFriend($user);
    expect($user->pendingFriendsOf)->toHaveCount(1);
});


it('does not create duplicate friend requests', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();
    $user->addFriend($friend);
    $user->addFriend($friend);
    expect($user->pendingFriendsOfMine)->not()->toHaveCount(2);
});


it('can accept friends', function () {

    $user = User::factory()->create();
    $friend = User::factory()->create();
    $user->addFriend($friend);
    $friend->acceptFriend($user);

    expect($user->acceptedFriendsOfMine)
        ->toHaveCount(1)
        ->pluck('id')->toContain($friend->id);;
});


it('can get all friends', function () {

    $user = User::factory()->create();
    $friend = User::factory()->create();
    $anotherFriend = User::factory()->create();
    $yetAnotherFriend = User::factory()->create();

    $user->addFriend($friend);
    $user->addFriend($anotherFriend);

    $friend->acceptFriend($user);


    $user->addFriend($yetAnotherFriend);

    $yetAnotherFriend->acceptFriend($user);

    expect($user->friends)->toHaveCount(2);
    expect($friend->friends)->toHaveCount(1);
    expect($anotherFriend->friends)->toHaveCount(0);
    expect($yetAnotherFriend->friends)->toHaveCount(1);

});


it('can remove a friend', function () {

    $user = User::factory()->create();
    $friend = User::factory()->create();
    $user->addFriend($friend);
    $friend->acceptFriend($user);

    $user->removeFriend($friend);

    expect($user->friends)->toHaveCount(0);
    expect($friend->friends)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(0);

});










