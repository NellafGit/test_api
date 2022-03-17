<?php

namespace App\Http\Controllers\Api\publ;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\publ\Book\StoreRequest;
use App\Http\Requests\Api\publ\Book\UpdateRequest;
use App\Http\Requests\Api\publ\Book\UploadRequest;
use App\Http\Resources\Api\publ\BookResource;
use App\Http\Resources\Api\publ\Collections\BookCollection;
use App\Models\Book;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    public function index(): Response
    {
        return response(new BookCollection(Book::all()));
    }

    public function store(StoreRequest $request): Response
    {
        $book = Book::create($request->safe()->except('authors'));

        $request->whenFilled('authors', function () use ($request, $book) {
            foreach ($request->safe()->collect()->get('authors') as $author) {
                $book->authors()->create($author);
            }
        });

        return response(new BookResource($book));
    }

    public function show(Book $book): Response
    {
        return response(new BookResource($book));
    }

    public function update(UpdateRequest $request, Book $book): Response
    {
        $book->authors()->update($request->validated());

        $request->whenFilled('authors', function () use ($request, $book) {
            foreach ($request->safe()->collect()->get('authors') as $author) {
                $book->authors()->create($author);
            }
        });

        return response(new BookResource($book));
    }

    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }

    public function uploadPhoto(Book $book, UploadRequest $request): JsonResponse
    {
        if ($request->hasFile('photo')) {
            $storage_link = $request->file('photo')->store('storage/images/books/'.$book->id);
            $name = pathinfo($storage_link)['basename'];
            $public_link = ('/images/books/'.$book->id.'/'.$name);

            $book->image()->create(compact('public_link', 'storage_link'));

            return response()->json(['message' => 'Uploaded'], 200);
        }

        return response()->json(['message' => 'Variable photo not found']);
    }
}
