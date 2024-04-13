<?php

namespace Tests\Unit\Model;

use App\Job;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobModelTest extends TestCase
{    
    use RefreshDatabase;

    public function testBasicTest()
    {        
        for($i = 0; $i < 100; $i++)
        {
            factory(Job::class)->create();
        }
        $count = Job::get()->count();
        $model = Job::find(rand(1, $count));
        $data = $model->toArray();
        print_r($data);
    
        $this->assertDatabaseHas('jobs', $data);
    
        $model->delete();
        $this->assertDatabaseMissing('jobs', $data);
    }
}
