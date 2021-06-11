<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Authorization;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthorizationController
 * @package App\Http\Controllers
 */
class AuthorizationController extends Controller
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
     * @param Request $request
     * @return Renderable
     */
    public function index(Request $request): Renderable
    {
        $authorizations = Authorization::where('user_id', '=', $request->user()->id)->where('district_id', '=', $request->user()->selected_district)->get();

        return view('authorization.index')->with('authorizations', $authorizations);

    }

    /**
     * Get create view
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        return view('authorization.create');
    }

    /**
     * Post create
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $this->validate($request, [
            'authorization_name' => 'required|string|max:50',
            'authorization_number' => 'required|string|max:15',
            'authorization_valid_from' => 'required|date',
            'authorization_valid_until' => 'required|date|after:authorization_valid_from',
        ]);

        $authorizationData = [
            'user_id' => $request->user()->id,
            'name' => $request->authorization_name,
            'number' => $request->authorization_number,
            'valid_from' => date(Helper::MYSQL_DATETIME_FORMAT, strtotime($request->authorization_valid_from)),
            'valid_until' => date(Helper::MYSQL_DATETIME_FORMAT, strtotime($request->authorization_valid_until)),
            'district_id' => $request->user()->selected_district,
        ];

        Authorization::create($authorizationData);

        $message = 'Upoważnienie ' . $request->authorization_name . ' zostało dodane prawidłowo.';
        return redirect()->route('authorization.index')->with('success', $message);
    }
}
