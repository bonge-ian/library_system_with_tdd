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
        $this->post('/authors', $this->data());
        $author = Author::all();

        $this->assertCount(1, $author);

        // assert if DOB is an instance of Carbon
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);
        $this->assertEquals('1995/02/22', $author->first()->dob->format('Y/m/d'));
    }

    /** @test */
    public function a_name_is_required()
    {
        $res = $this->post('/authors', array_merge($this->data(), ['name' => '']));

        $res->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_dob_is_required()
    {
        $res = $this->post('/authors', array_merge($this->data(), ['dob' => '']));

        $res->assertSessionHasErrors('dob');
    }

    protected function data()
    {
        return [
            'name' => 'Rey',
            'dob' => '02/22/1995'
        ];
    }
}
