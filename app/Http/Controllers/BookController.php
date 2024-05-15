<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Services\BookService;
class BookController extends Controller
{
    protected BookService $booksService;
    public function __construct(BookService $booksService)
    {
        $this->booksService = $booksService;
    }

    public function search(BookRequest $bookRequest)
    {
        $searchString = $bookRequest->input('q');
        $page = (int) $bookRequest->input('page', 1);
        $result = $this->booksService->searchBook($searchString, $page);
        $response = [];
        if($result->isNotEmpty() && $page <= $result->lastPage())
        {
            $data = collect($result->items())->map(function($book)
            {
                return [
                    'id' => $book->id,
                    'publisher' => $book->publisher,
                    'title' => $book->title,
                    'summary' => $book->summary,
                    'authors' => explode(',', $book->authors)
                ];
            });
            $response =  [
                'data' => $data,
                'currentPage' => $result->currentPage(),
                'total' => $result->total(),
                'lastPage' => $result->lastPage(),
            ];
        }

        return response()->json($response);
    }
}
