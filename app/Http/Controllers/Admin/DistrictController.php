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

        return view('admin.district.index')->withDistricts($districts);
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
            'district_name' => ['required', 'string', 'max:50'],
            'district_code' => ['required', 'string', 'max:15'],
            'district_description' => ['nullable', 'string', 'max:500'],
            'district_disabled' => ['required', 'boolean'],
        ]);

        District::create([
            'name' => $request->district_name,
            'code' => $request->district_code,
            'description' => $request->district_description,
            'disabled' => $request->district_disabled,
        ]);

        return redirect()->route('district.index')->withAlertsSuccess([
            ['title' => 'Dodano obwód!', 'message' => 'Obwód "' . $request->district_name . '" został dodany prawidłowo.'],
        ]);
    }

    /**
     * Get edit view
     *
     * @param $id
     * @return Renderable
     */
    public function edit($id): Renderable
    {
        $district = District::whereNull('parent_id')->findOrFail($id);

        return view('admin.district.edit')->withDistrict($district);
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
            'district_description' => ['nullable', 'string', 'max:500'],
            'district_disabled' => ['required', 'boolean'],
        ]);

        $district = District::whereNull('parent_id')->findOrFail($id);

        $district->forceFill([
            'description' => $request->district_description,
            'disabled' => $request->district_disabled,
        ])->save();

        return redirect()->back()->withAlertsSuccess([
            ['title' => 'Zapisano!', 'message' => 'Dane zostały zapisane poprawnie.'],
        ]);
    }
}
