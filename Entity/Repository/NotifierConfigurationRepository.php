<?php

/**
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @license: MIT
 */

namespace IDCI\Bundle\NotificationBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use IDCI\Bundle\SimpleMetadataBundle\Entity\Repository\MetadataRepository;

/**
 * NotifierConfigurationRepository.
 */
class NotifierConfigurationRepository extends AbstractEntityRepository
{
    /**
     * Extract tags data.
     *
     * @param array $tags
     *
     * @return array
     */
    public function extractTagsData(array $tags)
    {
        if (empty($tags)) {
            return array();
        }
        $extractedTags = array();

        foreach ($tags as $tag) {
            try {
                $tag = json_decode($tag, true);
                $extractedTags[] = $tag;
            } catch (\Exception $e) {
                return $extractedTags;
            }
        }

        return $extractedTags;
    }

    /**
     * Find templates.
     *
     * @param array  $tags
     * @param string $alias
     *
     * @return QueryBuilder
     */
    public function findNotifierConfigurationQueryBuilder(array $tags, $alias = 'notifierConfiguration')
    {
        $qb = $this->createQueryBuilder($alias);
        if (!empty($tags)) {
            $this->findbyTagsQueryBuilder($tags, $qb, $alias);
        }

        return $qb;
    }

    /**
     * @param array        $tags
     * @param QueryBuilder $qb
     * @param string       $alias
     *
     * @return QueryBuilder
     */
    public function findByTagsQueryBuilder(array $tags, QueryBuilder $qb = null, $alias = 'notifierConfiguration')
    {
        if (null === $qb) {
            $qb = $this->createQueryBuilder($alias);
        }
        $tags = $this->extractTagsData($tags);

        foreach ($tags as $key => $tag) {
            MetadataRepository::findByMetadataQueryBuilder(
                $qb,
                sprintf('%s.tags', $alias),
                array($tag),
                sprintf('metadata_%d', $key)
            );
        }

        return $qb;
    }
}
