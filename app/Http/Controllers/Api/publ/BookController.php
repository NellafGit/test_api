<?php

namespace App\Http\Controllers\Api\publ;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\publ\Book\StoreRequest;
use App\Http\Requests\Api\publ\Book\UpdateRequest;
use App\Http\Resources\Api\publ\BookResource;
use App\Http\Resources\Api\publ\Collections\BookCollection;
use App\Models\Book;
use Illuminate\Http\File;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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

        if ($request->hasFile('photo')) {
            $storage_link = Storage::disk('books')->putFile($book->id, new File($request->safe()->file('photo')));
            $public_link = Storage::disk('books')->url($storage_link);
            $book->image()->create(compact('public_link', 'storage_link'));
        }

        return response(new BookResource($book));
    }

    public function show(Book $book): Response
    {
        return response(new BookResource($book));
    }

    public function update(UpdateRequest $request, Book $book): Response
    {
        $book->update($request->safe()->except('authors'));

        $request->whenFilled('authors', function () use ($request, $book) {
            foreach ($request->safe()->collect()->get('authors') as $author) {
                $book->authors()->create($author);
            }
        });

        if ($request->hasFile('photo')) {
            $storage_link = Storage::disk('books')->putFile($book->id, new File($request->file('photo')));
            $public_link = Storage::disk('books')->url($storage_link);

            if ($book->image()->first()) {
                $oldPhoto = $book->image()->select('storage_link')->first();
                Storage::disk('books')->delete($oldPhoto->storage_link);
                $book->image()->update(compact('public_link', 'storage_link'));
            } else {
                $book->image()->create(compact('public_link', 'storage_link'));
            }
        }

        return response(new BookResource($book));
    }

    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
