<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class LanguageController extends Controller

{
    /**
 * @param $lang
 *
 * @return \Illuminate\Http\RedirectResponse
 */
    public function swap(Request $request)
    {
        // Almacenar el lenguaje en la session
        session()->put('locale', $request->lang);
        return redirect()->back();
    }
}