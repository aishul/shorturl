<?php

namespace App\Http\Controllers;

use App\Links;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('links.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('links.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'url' => "required",
        );

        $validator = Validator::make(Input::all(), $rules);

        // validate request
        if ($validator->fails()) {
            return Redirect::to('/')
                ->withErrors($validator)
                ->withInput();
        } else {
            // var code
            $code = str_random(7);

            // final url
            $finalUrl = URL::current();

            // add http
            $urlStr = Input::get('url');
            $parsed = parse_url(Input::get('url'));
            if (empty($parsed['scheme'])) {
                $urlStr = 'http://' . ltrim($urlStr, '/');
            }

            // store
            $link = new Links;
            $link->url = $urlStr;
            $link->clicks = 0;
            $link->code = $code;

            // if custom code form
            if (Input::get('custom'))
            {
                $link->code = Input::get('code');
                $link->save();
                return Redirect::to('/dashboard/stats/latest');
            }

            $link->save();

            // flash messages
            Session::flash('message', 'Successfully shortened url');

            $link = Links::where('code', $code)->get();
            Session::flash('url', $link[0]->code);

            // redirect
            return Redirect::to('/');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Links  $links
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        // Links::query()->where('code', $code)->update([
        //     'clicks' => \DB::raw('clicks + 1')
        //     ]);
        // $link = Links::where('code', $code)->get();
        $link = Links::where(\DB::raw('BINARY `code`'), $code)->first();
        if($link)
        {
            Links::query()->where(\DB::raw('BINARY `code`'), $code)->update([
                'clicks' => \DB::raw('clicks + 1')
                ]);
            return Redirect::to($link->url);
        }
        Session::flash('noCode', 'No URL found');
        return Redirect::to('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Links  $links
     * @return \Illuminate\Http\Response
     */
    public function edit(Links $links)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Links  $links
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Links $links)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Links  $links
     * @return \Illuminate\Http\Response
     */
    public function destroy(Links $links)
    {
        //
    }

    public function stats($filter)
    {
        $links = Links::orderBy('clicks', 'desc')->take(20)->get();
        if ($filter == "latest")
        {
            $links = Links::orderBy('created_at', 'desc')->take(20)->get();
        }
        return view('links.stats', ['links' => $links, 'filter' => $filter]);
    }

    public function success()
    {
        return view('links.success');
    }
}
