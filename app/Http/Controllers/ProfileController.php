<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
     * @param  Request  $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'street' => 'string|max:100|nullable',
            'house_number' => 'string|max:10|nullable',
            'zip_code' => 'string|max:10|nullable|postal_code:PL',
            'city' => 'string|max:50|nullable',
            'email' => 'string|max:250|email|nullable',
            'phone' => 'string|max:25|nullable|phone:PL',
        ]);

        $request->user()->forceFill([
            'street' => $request->street,
            'house_number' => $request->house_number,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'email' => $request->email,
            'phone' => $request->phone,
        ])->save();

        if(isset($request->password) || isset($request->password_confirmation)) {
            $this->validate($request, [
                'password' => 'confirmed|min:8|max:64',
            ]);

            $request->user()->forceFill([
                'password' => Hash::make($request->password),
            ])->save();
        }
        return redirect()->back()->with('success', true);
    }
}
