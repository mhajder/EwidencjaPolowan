<?php

namespace App\Http\Controllers;

use App\Models\District;
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
     * @return RedirectResponse
     */
    public function changeCurrent(Request $request, $district_id): RedirectResponse
    {
        $district = District::whereNull('parent_id')->findOrFail($district_id);

        $request->user()->forceFill([
            'selected_district' => $district->id,
        ])->save();

        return redirect()->back();
    }
}
