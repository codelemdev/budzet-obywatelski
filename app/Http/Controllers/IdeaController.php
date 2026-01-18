<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Idea;
use Illuminate\Contracts\View\View;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('idea.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea): View
    {
        return view('idea.show', [
            'idea' => $idea,
            'votesCount' => $idea->votes()->count(),
        ]);
    }
}
