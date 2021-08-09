<?php

namespace App\Services;

use App\Exceptions\NewsFromAnotherJournalistException;
use App\Exceptions\NewsNotFoundException;
use App\Exceptions\TypeFromAnotherJournalistException;
use App\Exceptions\TypeNotFoundException;
use App\Models\News;
use App\Models\Type;

/**
 * Class NewsService
 * @package App\Services
 */
class NewsService
{
    /**
     * @param int $news_id
     * @param string $requestName
     * @param string $requestTypeId
     * @return mixed
     * @throws NewsFromAnotherJournalistException
     * @throws NewsNotFoundException
     * @throws TypeFromAnotherJournalistException
     * @throws TypeNotFoundException
     */
    public function findByIdToUpdate(int $news_id, string $requestTypeId)
    {
        $news = News::find($news_id);

        // check if news exists
        if (!$news) {
            throw new NewsNotFoundException('Não foi encontrado uma notícia com o ID informado.');
        }
        // check if news is from the journalist authenticated
        if ($news->journalist_id != (auth()->user())->getAuthIdentifier()) {
            throw new NewsFromAnotherJournalistException('Esta notícia pertence à outro jornalista.');
        }
        // check if type exists
        if (!$type = Type::find($requestTypeId)) {
            throw new TypeNotFoundException('Não foi encontrado um tipo de notícia com o ID informado.');
        }
        // check if type is from the journalist authenticated
        if ($type->journalist_id != (auth()->user())->getAuthIdentifier()) {
            throw new TypeFromAnotherJournalistException('Este tipo de notícia pertence à outro jornalista.');
        }

        return $news;
    }

    /**
     * @param int $news_id
     * @return mixed
     * @throws NewsFromAnotherJournalistException
     * @throws NewsNotFoundException
     */
    public function findByIdToDelete(int $news_id)
    {
        $news = News::find($news_id);

        // check if news exists
        if (!$news) {
            throw new NewsNotFoundException('Não foi encontrado uma notícia com o ID informado.');
        }
        // check if news is from the journalist authenticated
        if ($news->journalist_id != (auth()->user())->getAuthIdentifier()) {
            throw new NewsFromAnotherJournalistException('Esta notícia pertence à outro jornalista.');
        }

        return $news;
    }

}
