<?php

declare(strict_types=1);

namespace App\UserPage\Application\UseCase\Command\UpdatePage;

use App\Shared\Application\Command\AbstractCommandHandler;
use App\Shared\Domain\Security\UserFetcherInterface;
use App\UserPage\Domain\Entity\Link;
use App\UserPage\Domain\Entity\Page;
use App\UserPage\Domain\Factory\LinkFactory;
use App\UserPage\Domain\Repository\LinkRepositoryInterface;
use App\UserPage\Domain\Repository\PageRepositoryInterface;
use Webmozart\Assert\Assert;

readonly class UpdatePageCommandHandler extends AbstractCommandHandler
{
    public function __construct(
        private PageRepositoryInterface $pageRepository,
        private LinkRepositoryInterface $linkRepository,
        private LinkFactory $linkFactory,
        private UserFetcherInterface $userFetcher,
    ) {
    }

    public function __invoke(UpdatePageCommand $c): void
    {
        Assert::notEmpty($c->description, 'Description cannot be empty');
        Assert::isArray($c->linksData, 'Links must be an array');
        Assert::minCount($c->linksData, 1, 'The page must have at least one link');

        foreach ($c->linksData as $link) {
            Assert::keyExists($link, 'url', 'Link URL is required');
            Assert::notEmpty($link['url'], 'Link URL cannot be empty');
            Assert::regex($link['url'], '/^https?:\/\/[^\s]+$/', 'Invalid URL format');

            Assert::keyExists($link, 'title', 'Link title is required');
            Assert::string($link['title'], 'Link title must be a string');
        }

        $user = $this->userFetcher->getAuthUser();

        $page = $this->pageRepository->findByIdWithLinks($c->pageId);

        Assert::notNull($page, 'Page not found');
        Assert::true($page->isOwnedBy($user->getId()), 'You don\'t have permission to edit this page');

        $page->setDescription($c->description);

        $existingLinks = $page->getLinks();
        $newLinkIds = [];

        foreach ($c->linksData as $linkData) {
            $link = $this->updateOrCreateLink($linkData, $page, $existingLinks);
            $newLinkIds[] = $link->getId();
        }

        $this->removeOldLinks($existingLinks, $newLinkIds, $page);

        $this->linkRepository->flush();
        $this->pageRepository->save($page);
    }

    private function updateOrCreateLink(array $linkData, Page $page, iterable $existingLinks): Link
    {
        if (!empty($linkData['id'])) {
            foreach ($existingLinks as $existingLink) {
                if ($existingLink->getId() === $linkData['id']) {
                    $existingLink->setUrl($linkData['url']);
                    $existingLink->setTitle($linkData['title']);

                    return $existingLink;
                }
            }
        }

        $link = $this->linkFactory->create($page, $linkData['url'], $linkData['title']);
        $this->linkRepository->persist($link);
        $page->addLink($link);

        return $link;
    }

    private function removeOldLinks(iterable $existingLinks, array $newLinkIds, Page $page): void
    {
        foreach ($existingLinks as $existingLink) {
            if (!in_array($existingLink->getId(), $newLinkIds)) {
                $page->removeLink($existingLink);
                $this->linkRepository->remove($existingLink);
            }
        }
    }
}
