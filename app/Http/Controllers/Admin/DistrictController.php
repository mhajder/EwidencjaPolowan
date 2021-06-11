<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class DistrictController
 * @package App\Http\Controllers\Admin
 */
class DistrictController extends Controller
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
     * Get index view
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        $districts = District::whereNull('parent_id')->get();

        return view('admin.district.index')->with('districts', $districts);
    }

    /**
     * Get create view
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        return view('admin.district.create');
    }

    /**
     * Post store
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'district_name' => 'required|string|max:50',
            'district_code' => 'required|string|max:15',
            'district_description' => 'string|max:500|nullable',
            'district_disabled' => 'required|boolean',
        ]);

        $districtData = [
            'name' => $request->district_name,
            'code' => $request->district_code,
            'description' => $request->district_description,
            'disabled' => $request->district_disabled,
        ];

        $newDistrict = District::create($districtData);

        $message = 'Obwód ' . $request->district_name . ' został dodany prawidłowo.';

        return redirect()->route('district.index')->with('success', $message);
    }

    /**
     * Get edit view
     *
     * @param $id
     * @return Renderable
     */
    public function edit($id): Renderable
    {
        $district = District::findOrFail($id);

        if (isset($district->id)) {
            if ($district->parent_id == null) {
                return view('admin.district.edit')->with('district', $district);
            }
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono obwodu', 'error_message' => 'Obwód o podanym ID nie istnieje!'));
    }

    /**
     * Patch update
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'district_description' => 'string|max:500|nullable',
            'district_disabled' => 'required|boolean',
        ]);

        $district = District::findOrFail($id);

        $district->forceFill([
            'description' => $request->district_description,
            'disabled' => $request->district_disabled,
        ])->save();

        return redirect()->back()->with('success', true);
    }
}
