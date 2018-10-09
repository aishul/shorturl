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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate
        // $rules = array(
        //     'url' => "required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/",
        // );
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
            $link->code = $code;
            $link->clicks = 0;
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
        Links::query()->where('code', $code)->update([
            'clicks' => \DB::raw('clicks + 1')
            ]);
        $link = Links::where('code', $code)->get();
        return Redirect::to($link[0]->url);
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
}
