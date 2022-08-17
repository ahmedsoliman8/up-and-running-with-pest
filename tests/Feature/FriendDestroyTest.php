<?php



use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\get;
use function Pest\Laravel\post;


uses(RefreshDatabase::class);


it('only allows authenticated users to post')
    ->expectGuest()->toBeRedirectedFor('/friends/1', 'patch');


it('deletes a friend request', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();

    $user->addFriend($friend);

    actingAs($user)
        ->delete('/friends/' . $friend->id);

    $this->assertDatabaseMissing('friends', [
        'user_id' => $user->id,
        'friend_id' => $friend->id,

    ]);
});
