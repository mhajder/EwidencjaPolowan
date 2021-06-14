<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class HuntingGroundController
 * @package App\Http\Controllers\Admin
 */
class HuntingGroundController extends Controller
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
     * @param $district_id
     * @return Renderable
     */
    public function index($district_id): Renderable
    {
        $district = District::whereNull('parent_id')->findOrFail($district_id);

        return view('admin.hunting-ground.index')->withDistrict($district)->withHuntingGrounds($district->huntingGrounds);
    }

    /**
     * Get create view
     *
     * @param $district_id
     * @return Renderable
     */
    public function create($district_id): Renderable
    {
        $district = District::whereNull('parent_id')->findOrFail($district_id);

        return view('admin.hunting-ground.create')->withDistrict($district);
    }

    /**
     * Post create
     *
     * @param Request $request
     * @param $district_id
     * @return Renderable|RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request, $district_id)
    {
        $district = District::whereNull('parent_id')->findOrFail($district_id);

        $this->validate($request, [
            'hunting_ground_name' => ['required', 'string', 'max:50'],
            'hunting_ground_code' => ['required', 'string', 'max:15'],
            'hunting_ground_description' => ['nullable', 'string', 'max:500'],
            'hunting_ground_disabled' => ['required', 'boolean'],
        ]);

        District::create([
            'name' => $request->hunting_ground_name,
            'code' => $request->hunting_ground_code,
            'description' => $request->hunting_ground_description,
            'disabled' => $request->hunting_ground_disabled,
            'parent_id' => $district->id,
        ]);

        return redirect()->route('hunting-ground.index', ['district_id' => $district->id])->withAlertsSuccess([
            ['title' => 'Dodano rewir!', 'message' => 'Rewir ' . $request->hunting_ground_name . ' został dodany prawidłowo.'],
        ]);
    }

    /**
     * Get edit view
     *
     * @param $district_id
     * @param $id
     * @return Renderable
     */
    public function edit($district_id, $id): Renderable
    {
        $huntingGround = District::where('parent_id', '=', $district_id)->findOrFail($id);

        return view('admin.hunting-ground.edit')->withHuntingGround($huntingGround);
    }

    /**
     * Patch update
     *
     * @param Request $request
     * @param $district_id
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $district_id, $id): RedirectResponse
    {
        $this->validate($request, [
            'hunting_ground_description' => ['nullable', 'string', 'max:500'],
            'hunting_ground_disabled' => ['required', 'boolean'],
        ]);

        $huntingGround = District::where('parent_id', '=', $district_id)->findOrFail($id);

        $huntingGround->forceFill([
            'description' => $request->hunting_ground_description,
            'disabled' => $request->hunting_ground_disabled,
        ])->save();

        return redirect()->back()->withAlertsSuccess([
            ['title' => 'Zapisano!', 'message' => 'Dane zostały zapisane poprawnie.'],
        ]);
    }
}
