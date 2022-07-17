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

        $response = $this->post("api/books", [
            "title" => "Cook book title",
            "author" => "Fahibram"
        ]);

        $response->assertOk();
        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function a_title_is_required()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post("api/books", [
            "title" => "",
            "author" => "Fahibram"
        ]);

        $response->assertSessionHasErrors("title");
    }

    /** @test */
    public function a_author_is_required()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post("api/books", [
            "title" => "Cook book title",
            "author" => ""
        ]);

        $response->assertSessionHasErrors("author");
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post("api/books", [
            "title" => "Cook book title",
            "author" => "Fahibram"
        ]);

        $book = Book::first();

        $response = $this->patch("api/books/{$book->id}", [
            "title" => "New title",
            "author" => "New author"
        ]);

        $this->assertEquals("New title", Book::first()->title);
        $this->assertEquals("New author", Book::first()->author);
    }
}
