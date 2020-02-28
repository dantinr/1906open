<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OauthController extends Controller
{

    public function githubCallback()
    {
        echo "<pre>";print_r($_GET);echo "</pre>";
    }
}
