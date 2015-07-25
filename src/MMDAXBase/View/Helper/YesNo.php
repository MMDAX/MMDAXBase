<?php

/*
 * Mateus Macedo Dos Anjos
 * @author Mateus Macedo Dos Anjos <mateusmacedodosanjos@gmail.com>
 * @copyright Copyright (c) 2013-2015 MMDAX Sofware BRA Inc.
 */

namespace MMDAXBase\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Description of YesNO
 *
 * @author mateus
 */
class YesNo extends AbstractHelper
{
    /**
     * 
     * @param boolena $param
     */
    public function __invoke($param)
    {
        if ($param) {
            return 'Yes';
        }
        
        return 'No';
    }
}
