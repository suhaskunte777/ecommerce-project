<?php

namespace App\Filters;

use Illuminate\Http\Request;

interface ApiFilterInterface {
    public function transform(Request $request);
}


