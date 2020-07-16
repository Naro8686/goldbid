<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MailingRequest;
use App\Jobs\MailingJob;
use App\Models\Mailing;

use App\Http\Controllers\Controller;


class MailingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.mailings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MailingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MailingRequest $request)
    {
        Mailing::query()->create($request->only(['title', 'subject', 'text']));
        return redirect()->route('admin.settings.mailing')->with('status', 'успешные дествия !');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Mailing $mailing
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Mailing $mailing)
    {
        return view('admin.mailings.edit', compact('mailing'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MailingRequest $request
     * @param \App\Models\Mailing $mailing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MailingRequest $request, Mailing $mailing)
    {
        $mailing->update($request->only(['title', 'subject', 'text', 'visibly']));
        return redirect()->route('admin.settings.mailing')->with('status', 'успешные дествия !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Mailing $mailing
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Mailing $mailing)
    {
        $mailing->delete();
        return redirect()->back()->with('status', 'success');
    }

    public function send(Mailing $mailing)
    {
        try {
            $status = 'status';
            $message = 'успешные дествия !';
            MailingJob::dispatchNow($mailing);
        }catch (\Exception $exception){
            $status = 'error';
            $message = $exception->getMessage();
        }
        return redirect()->route('admin.settings.mailing')->with($status,$message);
    }
}
