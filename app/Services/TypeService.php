<?php

namespace App\Services;

use App\Exceptions\TypeAlreadyExistsException;
use App\Exceptions\TypeFromAnotherJournalistException;
use App\Exceptions\TypeNotFoundException;
use App\Exceptions\TypeWithNewsAssociatedException;
use App\Models\News;
use App\Models\Type;

/**
 * Class TypeService
 * @package App\Services
 */
class TypeService
{
    /**
     * @param int $type_id
     * @param string $requestName
     * @return mixed
     * @throws TypeAlreadyExistsException
     * @throws TypeFromAnotherJournalistException
     * @throws TypeNotFoundException
     */
    public function findByIdToUpdate(int $type_id, string $requestName)
    {
        $type = Type::find($type_id);
        // check if news exists
        if (!$type) {
            throw new TypeNotFoundException('Não foi encontrado um tipo de notícia com o ID informado.');
        }
        // check if type is from the journalist authenticated
        if ($type->journalist_id != (auth()->user())->getAuthIdentifier()) {
            throw new TypeFromAnotherJournalistException('Este tipo de notícia pertence à outro jornalista.');
        }
        // check if news is from the journalist authenticated
        if (($type->name != $requestName) && count(Type::where([
                ['journalist_id', (auth()->user())->getAuthIdentifier()],
                ['name', $requestName],
                ['id', '<>', $type_id]
        ])->get())) {
            throw new TypeAlreadyExistsException('Já existe um tipo de notícia cadastrado com esse nome para o jornalista.');
        }
        return $type;
    }

    /**
     * @param int $type_id
     * @return mixed
     * @throws TypeFromAnotherJournalistException
     * @throws TypeNotFoundException
     * @throws TypeWithNewsAssociatedException
     */
    public function findByIdToDelete(int $type_id)
    {
        $type = Type::find($type_id);
        // check if news exists
        if (!$type) {
            throw new TypeNotFoundException('Não foi encontrado um tipo de notícia com o ID informado.');
        }
        // check if type is from the journalist authenticated
        if ($type->journalist_id != (auth()->user())->getAuthIdentifier()) {
            throw new TypeFromAnotherJournalistException('Este tipo de notícia pertence à outro jornalista.');
        }
        // check if news is from the journalist authenticated
        if (count(News::where('type_id', $type_id)->get())) {
            throw new TypeWithNewsAssociatedException('Não é possível deletar este tipo de notícia pois existem notícias associadas à ele.');
        }
        return $type;
    }

}
