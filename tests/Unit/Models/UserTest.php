<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Note;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
    }

    protected function tearDown(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
        parent::tearDown();
    }

    public function test_find_by_email_should_return_user_when_email_exists()
    {
        $email = 'test_' . uniqid() . '@example.com';
        
        $user = User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $foundUser = User::where('email', $email)->first();

        $this->assertNotNull($foundUser);
        $this->assertEquals($email, $foundUser->email);
    }

    public function test_find_by_email_should_return_null_when_email_not_exists()
    {
        $user = User::where('email', 'notexists_' . uniqid() . '@example.com')->first();
        $this->assertNull($user);
    }

    public function test_is_admin_should_return_true_for_admin_user()
    {
        $adminEmail = 'admin_' . uniqid() . '@example.com';
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => $adminEmail,
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
        
        $userEmail = 'user_' . uniqid() . '@example.com';
        $regularUser = User::create([
            'name' => 'User',
            'email' => $userEmail,
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $this->assertTrue($adminUser->isAdmin());
        $this->assertFalse($regularUser->isAdmin());
    }

    public function test_user_has_many_notes()
    {
        $userEmail = 'test_' . uniqid() . '@example.com';
        $user = User::create([
            'name' => 'Test User',
            'email' => $userEmail,
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        Note::create([
            'title' => 'Note 1',
            'description' => 'Description 1',
            'is_public' => true,
            'user_id' => $user->id
        ]);
        
        Note::create([
            'title' => 'Note 2',
            'description' => 'Description 2',
            'is_public' => true,
            'user_id' => $user->id
        ]);

        $this->assertCount(2, $user->notes);
    }
}