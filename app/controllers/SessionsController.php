<?php

class SessionsController extends \BaseController {


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('sessions.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()	{

        $input = Input::all();

        $attempt = Auth::attempt(['username' => $input['name'], 'password' => $input['password']], true);
        if ($attempt) {
            return Redirect::intended('/play')->with('flash_message', 'You are logged in');
        }

        return Redirect::back()->with("flash_message", "wrong credentials")->withInput();

    }

    public function destroy()
    {
        Auth::logout();
        return Redirect::intended("/login");
    }


}
