<?php

/**
 * Abstract Repository
 */

namespace FRUIT\GoogleServices\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Abstract Repository
 *
 * @author Tim Lochmüller
 */
abstract class AbstractRepository extends Repository
{

    /**
     * Create Query without Storage Respect
     *
     * @return QueryInterface
     */
    public function createQuery()
    {
        $query = parent::createQuery();
        $query->getQuerySettings()
            ->setRespectStoragePage(false);
        return $query;
    }
}
