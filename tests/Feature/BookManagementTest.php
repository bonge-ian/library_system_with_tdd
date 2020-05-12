<?php

namespace Tests\Feature;

use App\Author;
use App\Book;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        // $this->withoutExceptionHandling();
        $response = $this->post('/book', $this->data());
        $book = Book::first();

        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    /** @test */
    public function a_title_is_required()
    {
        //    $this->withoutExceptionHandling();
        $data = array_merge($this->data(), ['title' => '']);

        $reponse = $this->post('/book', $data);

        // assert for session error
        $reponse->assertSessionHasErrors('title');
    }

    /** @test */
    public function an_author_is_required()
    {
        //    $this->withoutExceptionHandling();
        $data = array_merge($this->data(), ['author_id' => '']);

        $reponse = $this->post('/book', $data);

        // assert for session error
        $reponse->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->post('/book', $this->data());
        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'New Book',
            'author_id' => 'Ndungu'
        ]);

        $this->assertEquals('New Book', $book->fresh()->title);
        $this->assertEquals(2, $book->fresh()->author_id);
        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $this->post('/book', $this->data());
        $book = Book::first();

        // assert if post request was a success
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

    /** @test */
    public function a_new_author_is_automatically_added()
    {
        $this->withoutExceptionHandling();
        $this->post('/book', $this->data());

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    protected function data()
    {
        return [
            'title' => 'Book one',
            'author_id' => 'Rey Donovan'
        ];
    }
}
