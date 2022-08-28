<?php

namespace Tests\Feature;

use App\Book;
use App\Author;
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

        $response = $this->post("api/books", $this->bookData());

        $response->assertOk();
        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function a_title_is_required()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post("api/books", array_merge($this->bookData(), ["title" => ""]));
        $response->assertSessionHasErrors("title");
    }

    /** @test */
    public function an_author_id_is_required()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post("api/books", array_merge($this->bookData(), ["author_id" => ""]));
        $response->assertSessionHasErrors("author_id");
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        // $this->withoutExceptionHandling();

        $this->post("api/books", $this->bookData());

        $book = Book::first();

        $response = $this->patch("api/books/{$book->id}", [
            "title" => "New title",
            "author_id" => "New author"
        ]);

        $this->assertEquals("New title", Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        // $this->withoutExceptionHandling();

        $this->post("api/books", $this->bookData());

        $this->assertCount(1, Book::all());

        $book = Book::first();
        $response = $this->delete("api/books/{$book->id}");

        $this->assertCount(0, Book::all());
    }

    /** @test */
    public function a_new_author_is_authomatically_added()
    {
        $this->withoutExceptionHandling();

        $this->post("api/books", $this->bookData());

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }

    private function bookData()
    {
        return [
            "title" => "Cook book title",
            "author_id" => "Fahibram"
        ];
    }
}
