<?php

namespace Piwicms\System\CoreBundle\Twig\Extensions;

use \Twig_Extension;

class UnreadExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("unread", array($this, 'countUnread'))
        );
    }

    public function countUnread($array)
    {
        $unread = 0;
        foreach ($array as $row) {
            if ($row['unread']) {
                $unread++;
            }
        }
        return $unread;
    }

    public function getName()
    {
        return 'Unread_extension';
    }
}