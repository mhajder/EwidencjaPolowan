<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Class ProfileController
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
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
        return view('profile');
    }

    /**
     * Patch update
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'street' => ['nullable', 'string', 'max:100'],
            'house_number' => ['nullable', 'string', 'max:10'],
            'zip_code' => ['nullable', 'string', 'max:10', 'post_code'],
            'city' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'max:250', 'email:rfc,dns,spoof',
                Rule::unique(User::class, 'email')->ignore($request->user()->id, 'id')],
            'phone' => ['nullable', 'string', 'max:25', 'phone:PL']
        ]);

        if (isset($request->phone)) {
            $request->request->add([
                'phone' => phone($request->phone, 'PL'),
            ]);
            $this->validate($request, [
                'phone' => [Rule::unique(User::class, 'phone')->ignore($request->user()->id, 'id')],
            ]);
        }

        $request->user()->forceFill([
            'street' => $request->street,
            'house_number' => $request->house_number,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'email' => $request->email,
            'phone' => $request->phone,
        ])->save();

        if (isset($request->password) || isset($request->password_confirmation)) {
            $this->validate($request, [
                'password' => ['required', 'string', 'confirmed', 'min:8', 'max:64'],
            ]);

            $request->user()->forceFill([
                'password' => Hash::make($request->password),
            ])->save();
        }
        return redirect()->back()->withAlertsSuccess([
            ['title' => 'Zapisano!', 'message' => 'Dane zostały zapisane poprawnie.'],
        ]);
    }
}
