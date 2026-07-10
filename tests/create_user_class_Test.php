<?php

namespace Tests;

use Models\User;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\TestCase;

include dirname(__DIR__) . '/src/database.php';

class Create_user_class_Test extends TestCase 
{
    public function test_it_creates_a_user() 
    {
        $id = Uuid::uuid4()->toString();
        
        $user = User::create([
            'id'       => $id,
            'username' => 'system_auditor',
            'email'    => 'audit@example.com',
            'password' => password_hash('strict_protocol_123', PASSWORD_BCRYPT)
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($id, $user->id);
        $this->assertEquals('system_auditor', $user->username);
    }
}