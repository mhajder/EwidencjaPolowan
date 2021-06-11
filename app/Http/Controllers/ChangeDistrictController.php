<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class ChangeDistrictController
 * @package App\Http\Controllers
 */
class ChangeDistrictController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get changeCurrent
     * Possibility to change the district by using the link.
     *
     * @param Request $request
     * @param $district_id
     * @return RedirectResponse|Renderable
     */
    public function changeCurrent(Request $request, $district_id)
    {
        $district = District::where('id', '=', $district_id)->whereNull('parent_id')->first();
        if (isset($district->id)) {
            $request->user()->forceFill([
                'selected_district' => $district->id,
            ])->save();
            return redirect()->back()->withInput();
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono obwodu', 'error_message' => 'Obwód o podanym ID nie istnieje!'));
    }
}
