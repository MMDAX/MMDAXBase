<?php

namespace MMDAXBase\Service;
use MMDAXBase\Service\AbstractServiceData;
use Doctrine\ORM\EntityManager;

/**
 * Description of ServiceData
 *
 * @author mateus
 */
class ServiceData extends AbstractServiceData
{
    
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }
    
}
