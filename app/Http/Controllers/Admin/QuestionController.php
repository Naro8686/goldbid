<?php

namespace App\Http\Controllers\Admin;

use App\Howitwork;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Question;
use App\Settings\ImageTrait;


class QuestionController extends Controller
{
    use ImageTrait;
    private const DIR = 'admin.questions.';

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view(self::DIR . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(QuestionRequest $request)
    {
        Question::query()->create($request->only(['title', 'description']));
        return redirect()->route('admin.pages.howitworks')->with('status', 'успешные дествия !');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $question = Question::findOrFail($id);
        return view(self::DIR . 'edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(QuestionRequest $request, $id)
    {
        $question = Question::query()->findOrFail($id);
        $question->update($request->only(['title', 'description']));
        return redirect()->route('admin.pages.howitworks')->with('status', 'успешные дествия !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        return redirect()->route('admin.pages.howitworks')->with('status', 'успешные дествия !');
    }
}
