<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function __construct()
    {

    }

    public function store(Request $request, Model $model)
    {

    }

    public function update(Request $request, Comment $comment)
    {
        //
    }

    public function destroy(Comment $comment)
    {
        //
    }
}