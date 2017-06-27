<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace LFramework\Caching;

/**
 * Description of Driver
 *
 * @author Lens
 */

use LFramework\Caching\Cache as Cache;

class CacheDriver extends Cache {
    
    public function initialize() {
        return $this;
    }
    
}
