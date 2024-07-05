<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Repositories\Articles\Exceptions\ArticleFetchFailedException;
use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Responses\RedirectResponse;
use App\Responses\TemplateResponse;
use App\Services\Articles\GetArticleService;
use Psr\Log\LoggerInterface;

class EditArticleController
{
    private GetArticleService $getArticleService;
    private LoggerInterface $logger;

    public function __construct(
        GetArticleService $getArticleService,
        LoggerInterface $logger
    ) {
        $this->getArticleService = $getArticleService;
        $this->logger = $logger;
    }

    public function __invoke(string $id): TemplateResponse
    {
        try {
            $article = $this->getArticleService->execute($id);
        } catch (ArticleNotFoundException $e) {
            $this->logger->error("Attempt to edit article that doesn't exist - {$e->getMessage()}");
            return new TemplateResponse("errors/404", ["message" => "Article doesn't exist."]);
        } catch (ArticleFetchFailedException $e) {
            $this->logger->error("Failed to fetch article - {$e->getMessage()}");
            return new TemplateResponse("errors/500", ["message" => "failed to fetch article"]);
        }
        return new TemplateResponse("articles/edit", ["article" => $article]);
    }
}