<?php

namespace Tests\Unit;

use App\Book;
use App\User;
use Tests\TestCase;
use App\Reservation;
use Exception;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_checked_out()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    /** @test */
    public function a_book_can_be_returned()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkout($user);

        $book->checkin($book);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    /** @test */
    public function throw_excpetion_if_book_is_not_checked_out()
    {
        $this->expectException(Exception::class);

        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();

        $book->checkin($book);
    }

    /** @test */
    public function a_user_checkout_a_book_twice()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkout($user);
        $book->checkin($book);

        $book->checkout($user);

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(), Reservation::find(2)->checked_out_at);       
    }

    /** @test */
    public function a_user_checkin_a_book_twice()
    {
        $book = factory(Book::class)->create();
        $user = factory(User::class)->create();
        $book->checkout($user);
        $book->checkin($book);

        $book->checkout($user);
        $book->checkin($book);

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertNotNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(), Reservation::find(2)->checked_in_at);
    }
}
