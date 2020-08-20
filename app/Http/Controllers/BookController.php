<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\SearchBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookService;
    public function __construct(
        BookService $bookService
    )
    {
        $this->bookService = $bookService;
    }

    public function index()
    {
        $books = $this->bookService->all();
        return $this->sendResponse($books, "Available books");
    }

    public function store(CreateBookRequest $request)
    {
        $book = $this->bookService->create($request);
        return $this->sendResponse($book, "Book created");
    }

    public function show($id)
    {
        $book = $this->bookService->show($id);
        return $this->sendResponse($book, "Book detail");
    }

    public function update(UpdateBookRequest $request, $id)
    {
        $this->bookService->update($request, $id);
    }

    public function destroy($id)
    {
        $this->bookService->delete($id);
        $this->sendResponse($id, 'Successfully deleted your book!');
    }

    public function search(Request $request)
    {
        $books = $this->bookService->search($request);
        $this->sendResponse($books, "Searched successfully");
    }
}
