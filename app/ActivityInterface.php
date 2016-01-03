<?php

namespace App;

/**
 *
 * @author absolux
 */
interface ActivityInterface {
    
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function author();
}
