<?php

use App\Models\User;
use App\Models\Chirp;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('zalogowany użytkownik może utworzyć chirpa', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/chirps', [
        'message' => 'To jest testowy wpis',
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('chirps', [
        'message' => 'To jest testowy wpis',
        'user_id' => $user->id,
    ]);
});

test('zalogowany użytkownik może dodać zdjęcie do chirpa', function () {
    Storage::fake('public');
    $user = User::factory()->create();
    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->actingAs($user)->post('/chirps', [
        'message' => 'Wpis ze zdjęciem',
        'image' => $file,
    ]);

    $response->assertRedirect('/');
    $this->assertDatabaseHas('chirps', ['message' => 'Wpis ze zdjęciem']);
    
    $chirp = Chirp::where('message', 'Wpis ze zdjęciem')->first();
    Storage::disk('public')->assertExists($chirp->image);
});

test('walidacja odrzuca zbyt długie wiadomości', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/chirps', [
        'message' => str_repeat('a', 256), // Limit to 255
    ]);

    $response->assertSessionHasErrors(['message']);
});

test('użytkownik może polubić i odlubić wpis', function () {
    $user = User::factory()->create();
    $chirp = Chirp::factory()->create();

    // Akcja Like
    $response = $this->actingAs($user)->postJson("/chirps/{$chirp->id}/like"); // Upewnij się, że ten URL odpowiada Twojemu z pliku web.php
    
    $response->assertOk()
             ->assertJsonPath('liked', true)
             ->assertJsonPath('count', 1);
             
    $this->assertDatabaseHas('likes', [
        'user_id' => $user->id,
        'chirp_id' => $chirp->id,
    ]);

    // Akcja Unlike (Toggle)
    $responseUnlike = $this->actingAs($user)->postJson("/chirps/{$chirp->id}/like");
    
    $responseUnlike->assertOk()
                   ->assertJsonPath('liked', false)
                   ->assertJsonPath('count', 0);
                   
    $this->assertDatabaseMissing('likes', [
        'user_id' => $user->id,
        'chirp_id' => $chirp->id,
    ]);
});

test('niezalogowany użytkownik nie może lajkować wpisów', function () {
    $chirp = Chirp::factory()->create();

    $response = $this->postJson("/chirps/{$chirp->id}/like");

    $response->assertUnauthorized(); // Kod 401
});