<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
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
        $users = User::all();

        return view('admin.user.index')->with('users', $users);
    }

    /**
     * Get create view
     *
     * @return Renderable
     */
    public function create(): Renderable
    {
        return view('admin.user.create');
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
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'pesel' => 'required|string|max:11|unique:App\Models\User|PESEL',
            'username' => 'required|string|max:50|unique:App\Models\User',
            'password' => 'required|string|confirmed|min:8|max:64',
            'permission' => 'required|integer|in:1,9',

            'street' => 'string|max:100|nullable',
            'house_number' => 'string|max:10|nullable',
            'zip_code' => 'string|max:10|nullable|postal_code:PL',
            'city' => 'string|max:50|nullable',
            'email' => 'string|max:250|email|nullable',
            'phone' => 'string|max:25|nullable|phone:PL',
        ]);

        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'pesel' => $request->pesel,
            'username' => $request->username,
            'password' => Hash::make($request['password']),
            'permission' => $request->permission,

            'street' => $request->street,
            'house_number' => $request->house_number,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        User::create($userData);

        $message = 'Użytkownik ' . $request->first_name . ' ' . $request->last_name . ' został dodany prawidłowo.';
        return redirect()->route('user.index')->with('success', $message);
    }

    /**
     * Get edit view
     *
     * @param $id
     * @return Renderable
     */
    public function edit($id): Renderable
    {
        $user = User::find($id);

        if (isset($user->id)) {
            if ($user->permission <= 9) {
                return view('admin.user.edit')->with('user', $user);
            }
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono użytkownika', 'error_message' => 'Użytkownik o podanym ID nie istnieje!'));
    }

    /**
     * Path update
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'permission' => 'required|integer|max:9|in:1,9',

            'street' => 'string|max:100|nullable',
            'house_number' => 'string|max:10|nullable',
            'zip_code' => 'string|max:10|nullable|postal_code:PL',
            'city' => 'string|max:50|nullable',
            'email' => 'string|max:250|email|nullable',
            'phone' => 'string|max:25|nullable|phone:PL',
        ]);

        $user = User::findOrFail($id);

        $user->forceFill([
            'permission' => $request->permission,

            'street' => $request->street,
            'house_number' => $request->house_number,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'email' => $request->email,
            'phone' => $request->phone,
        ])->save();

        if (isset($request->password) || isset($request->password_confirmation)) {
            $this->validate($request, [
                'password' => 'string|confirmed|min:8|max:64',
            ]);

            $user->forceFill([
                'password' => Hash::make($request->password),
            ])->save();
        }
        return redirect()->back()->with('success', true);
    }

    /**
     * Path block
     *
     * @param Request $request
     * @param $id
     * @return Renderable|RedirectResponse|Redirector
     */
    public function block(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (isset($user->id)) {
            if ($user->permission < 9 && $user->id != $request->user()->id) {
                if ($user->disabled == false) {
                    $user->forceFill([
                        'disabled' => true,
                    ])->save();
                } else {
                    $user->forceFill([
                        'disabled' => false,
                    ])->save();
                }
                $message = 'Użytkownik ' . $request->first_name . ' ' . $request->last_name . ' został zablokowany prawidłowo.';
                return redirect()->route('user.index')->with('success', $message);

            }
            return view('id_error')->with(array('error_title' => 'Błąd podczas edycji użytkownika', 'error_message' => 'Nie można zablokować samego siebie oraz użytkownika z funkcją administratora!'));
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono użytkownika', 'error_message' => 'Użytkownik o podanym ID nie istnieje!'));
    }
}
