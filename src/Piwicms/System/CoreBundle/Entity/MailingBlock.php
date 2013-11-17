<?php

namespace Piwicms\System\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MailingBlocks
 *
 * @ORM\Table(name="mailing_blocks")
 * @ORM\Entity(repositoryClass="Piwicms\System\CoreBundle\Repository\MailingBlockRepository")
 */
class MailingBlock
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var Mailing
     *
     * @ORM\ManyToOne(targetEntity="Mailing", inversedBy="mailingBlock")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mailing_id", referencedColumnName="id")
     * })
     */
    private $mailing;

    /**
     * @var Mailing
     *
     * @ORM\ManyToOne(targetEntity="ViewBlock")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="block_id", referencedColumnName="id")
     * })
     */
    private $viewBlock;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param \Piwicms\System\CoreBundle\Entity\Mailing $mailing
     */
    public function setMailing($mailing)
    {
        $this->mailing = $mailing;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Entity\Mailing
     */
    public function getMailing()
    {
        return $this->mailing;
    }

    /**
     * @param \Piwicms\System\CoreBundle\Entity\Mailing $viewBlock
     */
    public function setViewBlock($viewBlock)
    {
        $this->viewBlock = $viewBlock;
    }

    /**
     * @return \Piwicms\System\CoreBundle\Entity\Mailing
     */
    public function getViewBlock()
    {
        return $this->viewBlock;
    }
}
