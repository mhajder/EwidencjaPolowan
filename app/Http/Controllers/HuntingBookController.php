<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Animal;
use App\Models\Authorization;
use App\Models\District;
use App\Models\HuntedAnimal;
use App\Models\HuntingBook;
use App\Models\UsedHuntingGround;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Class HuntingBookController
 * @package App\Http\Controllers
 */
class HuntingBookController extends Controller
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
        $huntings = HuntingBook::where('district_id', '=', $request->user()->selected_district)->orderBy('id', 'DESC')->paginate(25);

        return view('hunting-book.index')->with('huntings', $huntings);

    }

    /**
     * Get create view
     *
     * @param Request $request
     * @return Renderable
     */
    public function create(Request $request): Renderable
    {
        $district = District::findOrFail($request->user()->selected_district);
        if ($district->disabled == 1) {
            return view('id_error')->with(array('error_title' => 'Obwód niedostępny', 'error_message' => 'Dany obwód aktualnie jest zablokowany!'));
        }

        // Check if user have valid authorisations for selected district
        $nowDatabaseFormat = date(Helper::MYSQL_DATETIME_FORMAT, strtotime(CarbonImmutable::now()));
        // TODO: Create function in model to get valid authorizations
        $authorizationsExists = Authorization::where('user_id', '=', $request->user()->id)
            ->where('district_id', '=', $request->user()->selected_district)
            ->where('valid_from', '<=', $nowDatabaseFormat)
            ->where('valid_until', '>=', $nowDatabaseFormat)
            ->exists();

        if ($authorizationsExists != 1) {
            return view('id_error')->with(array('error_title' => 'Brak upoważnień', 'error_message' => 'Nie masz dodanych żadnych ważnych upoważnień dla tego obowdu!'));
        }
        return view('hunting-book.create');
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
        $now = CarbonImmutable::now();
        $tomorrow = CarbonImmutable::now()->add(1, 'day');
        $dayAfterTomorrow = CarbonImmutable::now()->add(2, 'day');
        $nowDatabaseFormat = date(Helper::MYSQL_DATETIME_FORMAT, strtotime(CarbonImmutable::now()));

        $this->validate($request, [
            'hunting_authorization' => 'required|integer',
            'hunting_grounds' => 'required|array|min:1',
            'hunting_grounds.*' => [
                'required',
                'integer',
                Rule::exists(District::class, 'id')->where(function ($query) {
                    $query->whereNotNull('parent_id');
                }),
            ],
            'hunting_start' => 'required|date|after:now|before:' . $tomorrow,
            'hunting_end' => 'required|date|after:hunting_start|before:' . $dayAfterTomorrow,
            'hunting_description' => 'string|max:500|nullable',
        ]);

        // Check if authorization is valid
        // TODO: Create function in model to get valid authorizations
        $authorization = Authorization::find($request->hunting_authorization)
            ->where('user_id', '=', $request->user()->id)
            ->where('district_id', '=', $request->user()->selected_district)
            ->where('valid_from', '<=', $nowDatabaseFormat)
            ->where('valid_until', '>=', $nowDatabaseFormat)
            ->exists();

        if ($authorization != 1) {
            $message = 'Nieprawidłowe upoważnienie.';
            return back()->withInput()->with('error', $message);
        }

        // Check if the hunt is in a good period of time
        $requestDateStart = CarbonImmutable::parse($request->hunting_start);
        $requestDateEnd = CarbonImmutable::parse($request->hunting_end);

        // Check if hunt is ended before the hunt closing time
        if ($requestDateStart->hour <= 9) {
            $startDay = $requestDateStart->subUnitNoOverflow('hour', 24, 'day');
            $startDay = $startDay->add(9, 'hour');
            if ($startDay->lessThan($requestDateEnd)) {
                $message = 'Data końca polowania nie może być większa niż ' . $startDay->isoFormat('DD/MM/YYYY H:mm') . ', gdyż jest to data automatycznego domykania polowania.';
                return back()->withInput()->with('error', $message);
            }
        } else {
            $startDay = $requestDateStart->subUnitNoOverflow('hour', 24, 'day');
            $startDay = $startDay->add(1, 'day');
            $startDay = $startDay->add(9, 'hour');
            if ($startDay->lessThan($requestDateEnd)) {
                $message = 'Data końca polowania nie może być większa niż ' . $startDay->isoFormat('DD/MM/YYYY H:mm') . ', gdyż jest to data automatycznego domykania polowania.';
                return back()->withInput()->with('error', $message);
            }
        }

        // Check if have planed hunting
        $alreadyHunting = HuntingBook::where('end', '>', $nowDatabaseFormat)->where('canceled', '=', 0)->exists();
        if ($alreadyHunting == 1) {
            $message = 'Już aktualnie polujesz. Zakończ lub odwołaj inne polowanie aby móc stworzyć nowe.';
            return redirect()->route('hunting.index')->with('error', $message);
        }

        $huntingBookData = [
            'user_id' => $request->user()->id,
            'authorization_id' => $request->hunting_authorization,
            'district_id' => $request->user()->selected_district,
            'start' => date(Helper::MYSQL_DATETIME_FORMAT, strtotime($request->hunting_start)),
            'end' => date(Helper::MYSQL_DATETIME_FORMAT, strtotime($request->hunting_end)),
            'description' => $request->hunting_description,
        ];
        $newHuntingBook = HuntingBook::create($huntingBookData);

        foreach ($request->hunting_grounds as $hunting_ground) {
            $usedHuntingGroundsData = [
                'hunting_book_id' => $newHuntingBook->id,
                'hunting_ground_id' => $hunting_ground,
            ];
            UsedHuntingGround::create($usedHuntingGroundsData);
        }

        $message = 'Polowanie zostało dodane prawidłowo.';
        return redirect()->route('hunting.index')->with('success', $message);
    }

    /**
     * Patch cancel
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Renderable
     */
    public function cancel(Request $request, $id)
    {
        $hunting = HuntingBook::findOrFail($id);

        $now = CarbonImmutable::now();
        $huntingStartDate = CarbonImmutable::parse($hunting->start);

        if (isset($hunting->id) && $hunting->user_id == $request->user()->id && $huntingStartDate->greaterThan($now) && $hunting->canceled == 0) {
            HuntingBook::findOrFail($id)->update(['canceled' => true]);

            $message = 'Polowanie zostało anulowane prawidłowo.';
            return redirect()->route('hunting.index')->with('success', $message);
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono polowania', 'error_message' => 'Polowanie o podanym ID nie istnieje!'));
    }

    /**
     * Patch finish
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse|Renderable
     */
    public function finish(Request $request, $id)
    {
        $hunting = HuntingBook::findOrFail($id);

        $now = CarbonImmutable::now();
        $huntingStartDate = CarbonImmutable::parse($hunting->start);
        $huntingEndDate = CarbonImmutable::parse($hunting->end);

        if (isset($hunting->id) && $hunting->user_id == $request->user()->id && $huntingStartDate->lessThan($now) && $huntingEndDate->greaterThan($now) && $hunting->canceled == 0) {
            HuntingBook::findOrFail($id)->update(['end' => date(Helper::MYSQL_DATETIME_FORMAT, strtotime(CarbonImmutable::now()))]);

            $message = 'Polowanie zostało zakończone prawidłowo.';
            return redirect()->route('hunting.edit', ['id' => $hunting->id])->with('success', $message);
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono polowania', 'error_message' => 'Polowanie o podanym ID nie istnieje!'));
    }

    /**
     * Get edit view
     *
     * @param Request $request
     * @param $id
     * @return Renderable
     */
    public function edit(Request $request, $id): Renderable
    {
        $hunting = HuntingBook::findOrFail($id);

        $now = CarbonImmutable::now();
        $huntingEndDate = CarbonImmutable::parse($hunting->end);
        $huntingMaxEditDate = CarbonImmutable::parse($hunting->end)->add(1, 'day');

        if (isset($hunting->id) && $hunting->user_id == $request->user()->id && $now->greaterThan($huntingEndDate) && $now->lessThan($huntingMaxEditDate) && $hunting->canceled == 0) {
            $animalsDB = Animal::all();

            $animal_categories = $animalsDB->filter(function ($item) {
                return data_get($item, 'parent_id') == null;
            })->all();
            $animals = $animalsDB->filter(function ($item) {
                return data_get($item, 'parent_id') != null;
            })->all();

            $data = [
                'hunting' => $hunting,
                'animal_categories' => $animal_categories,
                'animals' => $animals
            ];

            return view('hunting-book.edit')->with($data);
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono polowania', 'error_message' => 'Polowanie o podanym ID nie istnieje!'));
    }

    /**
     * Patch edit
     *
     * @param Request $request
     * @param $id
     * @return Renderable|RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'hunting_shots' => 'required|integer|min:0|max:100',
            'hunting_description' => 'string|max:500|nullable',

            'animal_id_database' => 'array|nullable',
            'animal_category_id_stored' => 'array|nullable',
            'animal_id_stored' => 'array|nullable',
            'purpose_stored' => 'array|nullable',
            'tag_stored' => 'array|nullable',
            'weight_stored' => 'array|nullable',

            'animal_id_database.*' => [
                'required_unless:animal_id_database,null',
                'integer',
                Rule::exists(HuntedAnimal::class, 'id')->where(function ($query) use ($id) {
                    $query->where('hunting_book_id', '=', $id);
                }),
            ],
            'animal_category_id_stored.*' => [
                'required_unless:animal_id_database,null',
                'integer',
                Rule::exists(Animal::class, 'id')->where(function ($query) {
                    $query->whereNull('parent_id');
                }),
            ],
            'animal_id_stored.*' => [
                'required_unless:animal_id_database,null',
                'integer',
                Rule::exists(Animal::class, 'id')->where(function ($query) {
                    $query->whereNotNull('parent_id');
                }),
            ],
            'purpose_stored.*' => 'required_unless:animal_id_database,null|integer|min:1|max:5',
            'tag_stored.*' => 'required_unless:animal_id_database,null|string|max:100',
            'weight_stored.*' => 'required_unless:animal_id_database,null|integer|min:0',

            'animal_category_id' => 'array|nullable',
            'animal_id' => 'array|nullable',
            'purpose' => 'array|nullable',
            'tag' => 'array|nullable',
            'weight' => 'array|nullable',

            'animal_category_id.*' => [
                'required_unless:animal_category_id,null',
                'integer',
                Rule::exists(Animal::class, 'id')->where(function ($query) {
                    $query->whereNull('parent_id');
                }),
            ],
            'animal_id.*' => [
                'required_unless:animal_category_id,null',
                'integer',
                Rule::exists(Animal::class, 'id')->where(function ($query) {
                    $query->whereNotNull('parent_id');
                }),
            ],
            'purpose.*' => 'required_unless:animal_category_id,null|integer|min:1|max:5',
            'tag.*' => 'required_unless:animal_category_id,null|string|max:100',
            'weight.*' => 'required_unless:animal_category_id,null|integer|min:0',
        ]);

        $hunting = HuntingBook::findOrFail($id);

        $now = CarbonImmutable::now();
        $huntingEndDate = CarbonImmutable::parse($hunting->end);
        $huntingMaxEditDate = CarbonImmutable::parse($hunting->end)->add(1, 'day');

        if (isset($hunting->id) && $hunting->user_id == $request->user()->id && $now->greaterThan($huntingEndDate) && $now->lessThan($huntingMaxEditDate) && $hunting->canceled == 0) {
            $hunting->forceFill([
                'shots' => $request->hunting_shots,
                'description' => $request->hunting_description,
            ])->save();

            $huntedAnimals = $hunting->huntedAnimals->pluck('id')->toArray();

            if (count($huntedAnimals) >= 1 && !isset($request->animal_id_database)) {
                foreach ($huntedAnimals as $deletedHuntedAnimal) {
                    HuntedAnimal::find($deletedHuntedAnimal)->delete();
                }
            } elseif (isset($request->animal_id_database)) {
                foreach ($request->animal_id_database as $key => $animal_id_database) {
                    if (in_array($request->animal_id_database[$key], $huntedAnimals)) {
                        $animal = HuntedAnimal::find($request->animal_id_database[$key]);
                        $animal->forceFill([
                            'animal_category_id' => $request->animal_category_id_stored[$key],
                            'animal_id' => $request->animal_id_stored[$key],
                            'purpose' => $request->purpose_stored[$key],
                            'tag' => $request->tag_stored[$key],
                            'weight' => $request->weight_stored[$key],
                        ])->save();
                    }
                }

                $deletedHuntedAnimals = array_values(array_diff($huntedAnimals, $request->animal_id_database));
                foreach ($deletedHuntedAnimals as $deletedHuntedAnimal) {
                    if (in_array($deletedHuntedAnimal, $huntedAnimals)) {
                        HuntedAnimal::find($deletedHuntedAnimal)->delete();
                    }
                }
            }

            if (isset($request->animal_id)) {
                foreach ($request->animal_id as $key => $animal_id) {
                    HuntedAnimal::create([
                        'hunting_book_id' => $hunting->id,
                        'animal_category_id' => $request->animal_category_id[$key],
                        'animal_id' => $request->animal_id[$key],
                        'purpose' => $request->purpose[$key],
                        'tag' => $request->tag[$key],
                        'weight' => $request->weight[$key],
                    ]);
                }
            }
            return redirect()->back()->with('success', true);
        }
        return view('id_error')->with(array('error_title' => 'Nie znaleziono polowania', 'error_message' => 'Polowanie o podanym ID nie istnieje!'));
    }
}
