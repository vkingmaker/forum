<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;
use App\Favorite;

class FavoritesController extends Controller
{
    /**
     *  Create a new controller instance
     */
    function __construct()
    {
       $this->middleware('auth');
    }

    /**
     *  Store a new favorite in the database
     * @param Reply $reply
     * @return \Illuminate\Database\Eloquent\Model
     */

    public function store(Reply $reply)
    {
       $reply->favorite();

        return back();
    }
}
