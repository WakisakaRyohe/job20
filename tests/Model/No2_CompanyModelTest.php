<?php

namespace Tests\Unit\Model;

use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyModelTest extends TestCase
{    
    use RefreshDatabase;

    public function testBasicTest()
    {        
        for($i = 0; $i < 100; $i++)
        {
            factory(Company::class)->create();
        }
        $count = Company::get()->count();
        $model = Company::find(rand(1, $count));
        $data = $model->toArray();
        print_r($data);
    
        $this->assertDatabaseHas('companies', $data);
    
        $model->delete();
        $this->assertDatabaseMissing('companies', $data);
    }
}
