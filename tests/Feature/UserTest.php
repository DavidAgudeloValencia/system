<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'user_document' => '123456789',
            'apartment' => 'A101',
        ];

        $user = User::create($userData);

        $this->assertNotNull($user);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('johndoe@example.com', $user->email);
        $this->assertDatabaseHas('users', $userData);
    }

    /** @test */
    public function it_can_retrieve_a_user()
    {
        $user = User::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'user_document' => '987654321',
            'apartment' => 'B202',
        ]);

        $retrievedUser = User::find($user->id);

        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals('Jane Doe', $retrievedUser->name);
        $this->assertEquals('janedoe@example.com', $retrievedUser->email);
        $this->assertEquals('987654321', $retrievedUser->user_document);
        $this->assertEquals('B202', $retrievedUser->apartment);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = User::factory()->create([
            'name' => 'Alice Doe',
            'email' => 'alicedoe@example.com',
            'user_document' => '111222333',
            'apartment' => 'C303',
        ]);

        $user->update([
            'name' => 'Alice Updated',
            'apartment' => 'D404',
        ]);

        $this->assertEquals('Alice Updated', $user->name);
        $this->assertEquals('D404', $user->apartment);
        $this->assertDatabaseHas('users', [
            'name' => 'Alice Updated',
            'apartment' => 'D404',
        ]);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create([
            'name' => 'Bob Doe',
            'email' => 'bobdoe@example.com',
            'user_document' => '444555666',
            'apartment' => 'E505',
        ]);

        $this->assertTrue($user->delete());
        $this->assertDatabaseMissing('users', [
            'email' => 'bobdoe@example.com',
        ]);
    }

    /** @test */
    public function it_cannot_create_a_user_with_duplicate_email()
    {
        User::factory()->create(['email' => 'duplicate@example.com']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Duplicate User',
            'email' => 'duplicate@example.com',
            'user_document' => '123456789',
            'apartment' => 'A101',
        ]);
    }
}
