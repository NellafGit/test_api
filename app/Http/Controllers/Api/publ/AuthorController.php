<?php

namespace App\Http\Controllers\Api\publ;

use App\Helpers\CollectionHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\publ\Author\StoreRequest;
use App\Http\Requests\Api\publ\Author\UpdateRequest;
use App\Http\Resources\Api\publ\AuthorResource;
use App\Http\Resources\Api\publ\Collections\AuthorCollection;
use App\Models\Author;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

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

        if ($request->hasFile('photo')) {
            $storage_link = Storage::disk('authors')->putFile($author->id, new File($request->safe()->file('photo')));
            $public_link = Storage::disk('authors')->url($storage_link);
            $author->image()->create(compact('public_link', 'storage_link'));
        }

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

        if ($request->hasFile('photo')) {
            $storage_link = Storage::disk('authors')->putFile($author->id, new File($request->file('photo')));
            $public_link = Storage::disk('authors')->url($storage_link);

            if ($author->image()->first()) {
                $oldPhoto = $author->image()->select('storage_link')->first();
                Storage::disk('authors')->delete($oldPhoto->storage_link);
                $author->image()->update(compact('public_link', 'storage_link'));
            } else {
                $author->image()->create(compact('public_link', 'storage_link'));
            }
        }

        return response(new AuthorResource($author));
    }

    public function destroy(Author $author): JsonResponse
    {
        $author->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
