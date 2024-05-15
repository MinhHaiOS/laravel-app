<?php

namespace App\Services;

use App\Repositories\BookRepository;

class BookService
{
    protected BookRepository $bookRepository;
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }
    public function searchBook($searchString, $page = 1)
    {
        return $this->bookRepository->searchBook($searchString, $page);
    }


}
