<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRoles;
use App\Http\Controllers\Controller;
use App\Models\User;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('admin.user.index')->withUsers($users);
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
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'pesel' => ['required', 'PESEL', 'unique:App\Models\User'],
            'username' => ['required', 'string', 'max:50', 'unique:App\Models\User'],
            'password' => ['required', 'string', 'confirmed', 'min:8', 'max:64'],
            'permission' => ['required', new EnumValue(UserRoles::class, false)],

            'street' => ['nullable', 'string', 'max:100'],
            'house_number' => ['nullable', 'string', 'max:10'],
            'zip_code' => ['nullable', 'string', 'max:10', 'post_code'],
            'city' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'max:250', 'email'],
            'phone' => ['nullable', 'string', 'max:25', 'phone:PL'],
        ]);

        if (isset($request->phone)) {
            $request->phone = phone($request->phone, 'PL');
        }

        User::create([
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
        ]);

        return redirect()->route('user.index')->withAlertsSuccess([
            ['title' => 'Dodano użytkownika!', 'message' => 'Użytkownik ' . $request->name . ' został dodany prawidłowo.'],
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
        $user = User::findOrFail($id);

        return view('admin.user.edit')->withUser($user);
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
            'permission' => ['required', new EnumValue(UserRoles::class, false)],

            'street' => ['nullable', 'string', 'max:100'],
            'house_number' => ['nullable', 'string', 'max:10'],
            'zip_code' => ['nullable', 'string', 'max:10', 'post_code'],
            'city' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'max:250', 'email'],
            'phone' => ['nullable', 'string', 'max:25', 'phone:PL'],
        ]);

        $user = User::findOrFail($id);

        if (isset($request->phone)) {
            $request->phone = phone($request->phone, 'PL');
        }

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
                'password' => 'required|string|confirmed|min:8|max:64',
            ]);

            $user->forceFill([
                'password' => Hash::make($request->password),
            ])->save();
        }
        return redirect()->back()->withAlertsSuccess([
            ['title' => 'Zapisano!', 'message' => 'Dane zostały zapisane poprawnie.'],
        ]);
    }

    /**
     * Path block
     *
     * @param Request $request
     * @param $id
     * @return Renderable|RedirectResponse
     */
    public function block(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Check if the user is blocking himself
        if ($user->id == $request->user()->id) {
            return redirect()->route('user.edit', ['id' => $user->id])->withAlertsDanger([
                ['title' => 'Nie można zablokować samego siebie!', 'message' => 'System nie pozwala na blokadę własnego konta.'],
            ]);
        }

        // Check if the blocked user is the administrator
        if ($user->permission == 9) {
            return redirect()->route('user.edit', ['id' => $user->id])->withAlertsDanger([
                ['title' => 'Nie można zablokować użytkownika!', 'message' => 'Użytkownik posiada funkcję administratora, zmień funkcję na użytkownika aby móc go zablokować.'],
            ]);
        }

        if ($user->disabled == false) {
            $user->forceFill([
                'disabled' => true,
            ])->save();
        } else {
            $user->forceFill([
                'disabled' => false,
            ])->save();
        }

        return redirect()->route('user.edit', ['id' => $user->id])->withAlertsSuccess([
            ['title' => 'Zablokowano użytkownika!', 'message' => 'Użytkownik ' . $request->name . ' został zablokowany prawidłowo.'],
        ]);
    }
}
