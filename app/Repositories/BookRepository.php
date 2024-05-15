<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository
{
    CONST PER_PAGE = 20;
    public function searchBook($searchString, $page = 1)
    {
        return Book::query()
                   ->select('books.id', 'books.title', 'books.summary', 'books.publisher')
                   ->selectRaw('GROUP_CONCAT(authors.author_name SEPARATOR \',\') AS authors')
                   ->selectRaw('(MATCH(books.title, books.summary, books.publisher) AGAINST (? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) + MATCH(authors.author_name) AGAINST(? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)) AS score', [$searchString, $searchString])
                   ->join('book_authors', 'books.id', '=', 'book_authors.book_id')
                   ->join('authors', 'book_authors.author_id', '=', 'authors.id')
                   ->where(function ($query) use ($searchString) {
                       $query->whereRaw('MATCH(books.title, books.summary, books.publisher) AGAINST (? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)', [$searchString])
                             ->orWhereRaw('MATCH(authors.author_name) AGAINST(? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)', [$searchString]);
                    })
                   ->groupBy('books.id')
                   ->orderBy('score', 'DESC')
                   ->paginate(self::PER_PAGE,['*'], 'page', $page);
    }
}


