<?php

namespace Tests\Feature;

use App\Author;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_author_can_be_created()
    {
        $this->withoutExceptionHandling();
        $this->post('/author', [
            'name' => 'Rey',
            'dob' => '02/22/1995'
        ]);
        $author = Author::all();

        $this->assertCount(1, $author);

        // assert if DOB is an instance of Carbon
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);
        $this->assertEquals('1995/02/22', $author->first()->dob->format('Y/m/d'));
    }
}
