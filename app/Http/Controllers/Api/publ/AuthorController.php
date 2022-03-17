<?php

namespace App\Http\Controllers\Api\publ;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\publ\Author\StoreRequest;
use App\Http\Requests\Api\publ\Author\UpdateRequest;
use App\Http\Requests\Api\publ\Author\UploadRequest;
use App\Http\Resources\Api\publ\AuthorResource;
use App\Http\Resources\Api\publ\Collections\AuthorCollection;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    public function index(): Response
    {
        return response(new AuthorCollection(Author::all()));
    }

    public function store(StoreRequest $request): Response
    {
        $author = Author::create($request->safe()->except('books'));

        $request->whenFilled('books', function () use ($request, $author) {
            foreach ($request->safe()->collect()->get('books') as $book) {
                $author->books()->create($book);
            }
        });

        return response(new AuthorResource($author));
    }

    public function show(Author $author): Response
    {
        return response(new AuthorResource($author));
    }

    public function update(UpdateRequest $request, Author $author): Response
    {
        $author->update($request->safe()->except('books'));
        $request->whenFilled('books', function () use ($request, $author) {
            foreach ($request->safe()->collect()->get('books') as $book) {
                $author->books()->update($book);
            }
        });

        return response(new AuthorResource($author));
    }

    public function destroy(Author $author): JsonResponse
    {
        $author->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }

    public function uploadPhoto(Author $author, UploadRequest $request): JsonResponse
    {
        if ($request->hasFile('photo')) {
            $storage_link = $request->file('photo')->store('storage/images/authors/'.$author->id);
            $name = pathinfo($storage_link)['basename'];
            $public_link = ('/images/authors/'.$author->id.'/'.$name);

            $author->image()->create(compact('public_link', 'storage_link'));

            return response()->json(['message' => 'Uploaded'], 200);
        }

        return response()->json(['message' => 'Variable photo not found']);
    }
}
