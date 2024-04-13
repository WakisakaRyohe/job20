<?php

namespace Tests\Unit\Model;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserModelTest extends TestCase
{    
    use RefreshDatabase;

    public function testBasicTest()
    {        
        for($i = 0; $i < 100; $i++)
        {
            factory(User::class)->create();
        }
        $count = User::get()->count();
        $model = User::find(rand(1, $count));
        $data = $model->toArray();
        print_r($data);
    
        $this->assertDatabaseHas('users', $data);
    
        $model->delete();
        $this->assertDatabaseMissing('users', $data);
    }
}
