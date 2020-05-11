<?php

namespace Tests\Feature;

use App\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/book', [
            'title' => 'Book one',
            'author' => 'Sony vaio'
        ]);

        // assert for successful reponse
        $response->assertOk();

        $this->assertCount(1, Book::all());
    }

   /** @test */
   public function a_title_is_required ()
   {
    //    $this->withoutExceptionHandling();
       $data = [
           'title' => '',
           'author' => 'rey'
       ];

       $reponse = $this->post('/book', $data);

       // assert for session error
       $reponse->assertSessionHasErrors('title');
   }

    /** @test */
    public function an_author_is_required()
    {
        //    $this->withoutExceptionHandling();
        $data = [
            'tltle' => 'Duracoat techniques',
            'author' => ''
        ];

        $reponse = $this->post('/book', $data);

        // assert for session error
        $reponse->assertSessionHasErrors('author');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $this->post('/book', [
            'title' => 'Cool Book',
            'author' => 'Rey'
        ]);
        $book = Book::first();

        $response = $this->patch('/book/' . $book->id, [
            'title' => 'New Book',     
            'author' => 'Ndungu'
        ]);

        $response->assertOk();
        $this->assertEquals('New Book', Book::first()->title);
        $this->assertEquals('Ndungu', Book::first()->author);

    }
}
