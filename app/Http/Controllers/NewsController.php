<?php

namespace App\Http\Controllers;

use App\Exceptions\TypeFromAnotherJournalistException;
use App\Exceptions\TypeNotFoundException;
use App\Http\Requests\NewsRequest;
use App\Models\News;
use App\Models\Type;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;

/**
 * Class NewsController
 * @package App\Http\Controllers
 */
class NewsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param NewsRequest $request
     * @return JsonResponse
     * @throws TypeFromAnotherJournalistException
     * @throws TypeNotFoundException
     */
    public function create(NewsRequest $request): JsonResponse
    {
        // makes the sanitization
        $request->sanitize();
        // makes the validation
        $request->validated();

        // check if type exists
        if (!$type = Type::find($request->type_id)) {
            throw new TypeNotFoundException('Não foi encontrado um tipo de notícia com o ID informado.');
        }
        // check if news is from the journalist authenticated
        if ($type->journalist_id != (auth()->user())->getAuthIdentifier()) {
            throw new TypeFromAnotherJournalistException('Este tipo de notícia pertence à outro jornalista.');
        }

        $news = new News;
        $news->journalist_id = (auth()->user())->getAuthIdentifier();
        $news->type_id = $request->type_id;
        $news->title = $request->title;
        $news->description = $request->description;
        $news->body = $request->body;
        $news->img_link = $request->img_link;
        $news->save();

        return response()->json($news, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NewsRequest $request
     * @param int $news_id
     * @return JsonResponse
     * @throws TypeFromAnotherJournalistException
     * @throws TypeNotFoundException
     * @throws \App\Exceptions\NewsFromAnotherJournalistException
     * @throws \App\Exceptions\NewsNotFoundException
     */
    public function update(NewsRequest $request, int $news_id): JsonResponse
    {
        // makes the sanitization
        $request->sanitize();
        // makes the validation
        $request->validated();

        $news = (new NewsService())->findByIdToUpdate($news_id, $request->type_id);
        $news->type_id = $request->type_id;
        $news->title = $request->title;
        $news->description = $request->description;
        $news->body = $request->body;
        $news->img_link = $request->img_link;
        $news->save();

        return response()->json($news);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $news_id
     * @return JsonResponse
     * @throws \App\Exceptions\NewsFromAnotherJournalistException
     * @throws \App\Exceptions\NewsNotFoundException
     */
    public function delete(int $news_id): JsonResponse
    {
        $news = (new NewsService())->findByIdToDelete($news_id);
        $news->delete();

        return response()->json(['message' => 'Deleted Successfully.'], 204);
    }

    /**
     * List all news from the authenticated journalist.
     *
     * @return JsonResponse
     */
    public function allByMe(): JsonResponse
    {
        $news = News::where('journalist_id', (auth()->user())->getAuthIdentifier())->get();
        return response()->json($news);
    }

    /**
     * List all news from the authenticated journalist by type.
     *
     * @param int $type_id
     * @return JsonResponse
     * @throws TypeFromAnotherJournalistException
     * @throws TypeNotFoundException
     */
    public function allByMeByType(int $type_id): JsonResponse
    {
        // check if type exists
        if (!$type = Type::find($type_id)) {
            throw new TypeNotFoundException('Não foi encontrado um tipo de notícia com o ID informado.');
        }
        // check if news is from the journalist authenticated
        if ($type->journalist_id != (auth()->user())->getAuthIdentifier()) {
            throw new TypeFromAnotherJournalistException('Este tipo de notícia pertence à outro jornalista.');
        }

        $news = News::where('journalist_id', '=', (auth()->user())->getAuthIdentifier())
            ->where('type_id', '=', $type_id)
            ->get();
        return response()->json($news);
    }
}
