<?php

namespace App\Http\Controllers;

use App\Exceptions\TypeAlreadyExistsException;
use App\Http\Requests\TypeRequest;
use App\Models\Type;
use App\Services\TypeService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TypeController
 * @package App\Http\Controllers
 */
class TypeController extends Controller
{
    /**
     * @param TypeRequest $request
     * @return JsonResponse
     * @throws TypeAlreadyExistsException
     */
    public function create(TypeRequest $request): JsonResponse
    {
        // makes the sanitization
        $request->sanitize();
        // makes the validation
        $request->validated();

        // check if already exists this type for the journalist authenticated
        if (count(Type::where([['journalist_id', (auth()->user())->getAuthIdentifier()], ['name', $request->name]])->get())) {
            throw new TypeAlreadyExistsException('Já existe um tipo de notícia cadastrado com esse nome para o jornalista.');
        }

        $type = new Type;
        $type->journalist_id = (auth()->user())->getAuthIdentifier();
        $type->name = $request->name;
        $type->save();

        return response()->json($type, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TypeRequest $request
     * @param int $type_id
     * @return JsonResponse
     * @throws TypeAlreadyExistsException
     * @throws \App\Exceptions\TypeFromAnotherJournalistException
     * @throws \App\Exceptions\TypeNotFoundException
     */
    public function update(TypeRequest $request, int $type_id): JsonResponse
    {
        // makes the sanitization
        $request->sanitize();
        // makes the validation
        $request->validated();

        $type = (new TypeService())->findByIdToUpdate($type_id, $request->name);
        $type->name = $request->name;
        $type->save();

        return response()->json($type);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $type_id
     * @return Response
     * @throws \App\Exceptions\TypeFromAnotherJournalistException
     * @throws \App\Exceptions\TypeNotFoundException
     * @throws \App\Exceptions\TypeWithNewsAssociatedException
     */
    public function delete(int $type_id): Response
    {
        $type = (new TypeService())->findByIdToDelete($type_id);
        $type->delete();

        return response()->json(['message' => 'Deleted Successfully.'], 204);
    }

    /**
     * List all types of news from the authenticated journalist.
     *
     * @return JsonResponse
     */
    public function allByMe(): JsonResponse
    {
        $types = Type::where('journalist_id', (auth()->user())->getAuthIdentifier())->get();
        return response()->json($types);
    }
}
