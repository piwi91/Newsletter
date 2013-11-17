<?php

namespace Piwicms\System\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MailingStatisticsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MailingStatisticRepository extends EntityRepository
{
    public function findTotalReadedNewsletterByNewsletter($newsletterId)
    {
        $qb = $this->createQueryBuilder('mailingStatistic');
        $qb ->select(
                $qb->expr()->countDistinct('mailingStatistic.mailingListUser')
            )
            ->where('mailingStatistic.mailing = :newsletterId')
            ->andWhere('mailingStatistic.type = :type')
            ->setParameter('newsletterId', $newsletterId)
            ->setParameter('type', 'viewed');
        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findReadedNewsletterByNewsletter($newsletterId)
    {
        $qb = $this->createQueryBuilder('mailingStatistic');
        $qb ->select('DATE(mailingStatistic.datetime) AS date, count(mailingStatistic.id) AS amount')
            ->innerJoin('mailingStatistic.mailing', 'mailing')
            ->where('mailing.id = :newsletterId')
            ->andWhere('mailingStatistic.type = :type')
            ->groupBy('date')
            ->setParameter('newsletterId', $newsletterId)
            ->setParameter('type', 'viewed');
        return $qb->getQuery()->getScalarResult();
    }

    public function findClickedUrlByNewsletter($newsletterId, $groupBy)
    {
        $qb = $this->createQueryBuilder('mailingStatistic');
        $qb ->select('DATE(mailingStatistic.datetime) AS date, mailingStatistic.url, count(mailingStatistic.id) AS amount')
            ->innerJoin('mailingStatistic.mailing', 'mailing')
            ->where('mailing.id = :newsletterId')
            ->andWhere('mailingStatistic.type = :type')
            ->groupBy($groupBy)
            ->setParameter('newsletterId', $newsletterId)
            ->setParameter('type', 'url');
        return $qb->getQuery()->getScalarResult();
    }
}
