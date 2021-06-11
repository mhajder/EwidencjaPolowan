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
        $district = District::whereNull('parent_id')->where('id', '=', $district_id)->first();
        if (isset($district->id)) {
            $huntingGrounds = District::where('parent_id', '=', $district->id)->get();
            return view('admin.hunting-ground.index')->with('huntingGrounds', $huntingGrounds);
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono obwodu', 'error_message' => 'Obwód o podanym ID nie istnieje!'));
    }

    /**
     * Get create view
     *
     * @param $district_id
     * @return Renderable
     */
    public function create($district_id): Renderable
    {
        $district = District::whereNull('parent_id')->where('id', '=', $district_id)->first();
        if (isset($district->id)) {
            return view('admin.hunting-ground.create')->with('district', $district);
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono obwodu', 'error_message' => 'Obwód o podanym ID nie istnieje!'));
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
        $district = District::whereNull('parent_id')->where('id', '=', $district_id)->first();
        if (isset($district->id)) {
            $this->validate($request, [
                'hunting_ground_name' => 'required|string|max:50',
                'hunting_ground_code' => 'required|string|max:15',
                'hunting_ground_description' => 'string|max:500|nullable',
                'hunting_ground_disabled' => 'required|boolean',
            ]);

            $huntingGroundData = [
                'name' => $request->hunting_ground_name,
                'code' => $request->hunting_ground_code,
                'description' => $request->hunting_ground_description,
                'disabled' => $request->hunting_ground_disabled,
                'parent_id' => $district->id,
            ];

            $newHuntingGround = District::create($huntingGroundData);

            $message = 'Rewir ' . $request->district_name . ' został dodany prawidłowo.';

            return redirect()->route('hunting-ground.index', ['district_id' => $district->id])->with('success', $message);
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono obwodu', 'error_message' => 'Obwód o podanym ID nie istnieje!'));
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
        $huntingGround = District::findOrFail($id);

        if (isset($huntingGround->id)) {
            if ($huntingGround->parent_id != null) {
                return view('admin.hunting-ground.edit')->with('huntingGround', $huntingGround);
            }
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono rewiru', 'error_message' => 'Rewir o podanym ID nie istnieje!'));
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
            'hunting_ground_description' => 'string|max:500|nullable',
            'hunting_ground_disabled' => 'required|boolean',
        ]);

        $district = District::findOrFail($id);

        $district->forceFill([
            'description' => $request->hunting_ground_description,
            'disabled' => $request->hunting_ground_disabled,
        ])->save();

        return redirect()->back()->with('success', true);
    }
}
