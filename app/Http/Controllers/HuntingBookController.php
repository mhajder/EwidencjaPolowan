<?php

namespace App\Http\Controllers;

use App\Enums\HuntedAnimalPurposes;
use App\Helpers\Helper;
use App\Models\Animal;
use App\Models\Authorization;
use App\Models\District;
use App\Models\HuntedAnimal;
use App\Models\HuntingBook;
use App\Models\UsedHuntingGround;
use BenSampo\Enum\Rules\EnumValue;
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
        $animalsArray = Animal::orderBy('id', 'ASC')->get()->toArray();

        return view('hunting-book.index')->withHuntings($huntings)->withAnimals($animalsArray);
    }

    /**
     * Get create view
     *
     * @param Request $request
     * @return Renderable|RedirectResponse
     */
    public function create(Request $request)
    {
        $district = District::whereNull('parent_id')->findOrFail($request->user()->selected_district);

        // Check if district is disabled
        if ($district->disabled == 1) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Obwód niedostępny!', 'message' => 'Dany obwód aktualnie jest zablokowany! Nie ma możliwości tworzenia polowań!'],
            ]);
        }

        // Check if district has hunting grounds and if are they blocked
        $availableHuntingGrounds = $district->availableHuntingGrounds();
        if (!$availableHuntingGrounds->exists()) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Brak dostępnych rewirów!', 'message' => 'Nie ma żadnego dostępnego rewiru dla tego obwodu.'],
            ]);
        }

        // Check if user have valid authorisations for selected district
        $validAuthorizationsInGivenDistrict = $request->user()->validAuthorizationsInGivenDistrict($request->user()->selected_district);
        if (!$validAuthorizationsInGivenDistrict->exists()) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Brak upoważnień!', 'message' => 'Nie masz dodanych żadnych ważnych upoważnień dla danego obowdu!'],
            ]);
        }

        return view('hunting-book.create')
            ->withHuntingGrounds($availableHuntingGrounds->get())
            ->withAuthorizations($validAuthorizationsInGivenDistrict->get());
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
        $tomorrow = CarbonImmutable::now()->add(1, 'day');
        $dayAfterTomorrow = CarbonImmutable::now()->add(2, 'day');

        $this->validate($request, [
            'hunting_authorization' => ['required', 'integer',
                Rule::exists(Authorization::class, 'id')->where(function ($query) use ($request) {
                    $nowDatabaseFormat = date(Helper::MYSQL_DATETIME_FORMAT, strtotime(CarbonImmutable::now()));
                    $query->where('user_id', '=', $request->user()->id)
                        ->where('district_id', '=', $request->user()->selected_district)
                        ->where('valid_from', '<=', $nowDatabaseFormat)
                        ->where('valid_until', '>=', $nowDatabaseFormat);
                }),
            ],
            'hunting_grounds' => ['required', 'array', 'min:1'],
            'hunting_grounds.*' => ['required', 'integer',
                Rule::exists(District::class, 'id')->where(function ($query) {
                    $query->whereNotNull('parent_id');
                }),
            ],
            'hunting_start' => ['required', 'date', 'after:now', 'before:hunting_end', 'before:' . $tomorrow],
            'hunting_end' => ['required', 'date', 'after:hunting_start', 'before:' . $dayAfterTomorrow],
            'hunting_description' => ['nullable', 'string', 'max:500'],
        ]);

        $district = District::whereNull('parent_id')->findOrFail($request->user()->selected_district);

        // Check if district is disabled
        if ($district->disabled == 1) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Obwód niedostępny!', 'message' => 'Dany obwód aktualnie jest zablokowany! Nie ma możliwości tworzenia polowań!'],
            ]);
        }

        // Check if district has hunting grounds and if are they blocked
        if (!$district->availableHuntingGrounds()->exists()) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Brak dostępnych rewirów!', 'message' => 'Nie ma żadnego dostępnego rewiru dla tego obwodu.'],
            ]);
        }

        // Check if user have valid authorisations for selected district
        if (!$request->user()->validAuthorizationsInGivenDistrict($request->user()->selected_district)->exists()) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Brak upoważnień!', 'message' => 'Nie masz dodanych żadnych ważnych upoważnień dla danego obowdu!'],
            ]);
        }

        // Check if the hunt is in a good period of time
        $requestDateStart = CarbonImmutable::parse($request->hunting_start);
        $requestDateEnd = CarbonImmutable::parse($request->hunting_end);

        $startDay = $requestDateStart->subUnitNoOverflow('hour', 24, 'day');
        if ($requestDateStart->hour >= 9) {
            $startDay = $startDay->add(1, 'day');
        }
        $startDay = $startDay->add(9, 'hour');

        // Check if hunt is ended before the hunt closing time
        if ($startDay->lessThan($requestDateEnd)) {
            return redirect()->back()->withInput()->withAlertsDanger([
                ['title' => 'Niepoprawna data końca polowania!', 'message' => 'Data końca polowania nie może być większa niż ' . $startDay->format(Helper::HUNTING_DATE_RANGE_PICKER_FORMAT) . ', gdyż jest to data automatycznego domykania polowania.'],
            ]);
        }

        // Check if user is hunting in given district or if have planed hunting
        if ($request->user()->checkIfUserIsHuntingInGivenDistrict($request->user()->selected_district)) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Już aktualnie polujesz!', 'message' => 'Zakończ lub odwołaj inne polowanie w danym obwodzie, aby móc stworzyć nowe.'],
            ]);
        }

        $newHuntingBook = HuntingBook::create([
            'user_id' => $request->user()->id,
            'authorization_id' => $request->hunting_authorization,
            'district_id' => $request->user()->selected_district,
            'start' => date(Helper::MYSQL_DATETIME_FORMAT, strtotime($request->hunting_start)),
            'end' => date(Helper::MYSQL_DATETIME_FORMAT, strtotime($request->hunting_end)),
            'description' => $request->hunting_description,
        ]);

        foreach ($request->hunting_grounds as $hunting_ground) {
            UsedHuntingGround::create([
                'hunting_book_id' => $newHuntingBook->id,
                'hunting_ground_id' => $hunting_ground,
            ]);
        }

        return redirect()->route('hunting.index')->withAlertsSuccess([
            ['title' => 'Dodano polowanie!', 'message' => 'Polowanie zostało dodane prawidłowo.'],
        ]);
    }

    /**
     * Patch cancel
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function cancel(Request $request, $id): RedirectResponse
    {
        $hunting = HuntingBook::where('user_id', '=', $request->user()->id)
            ->where('canceled', '=', 0)
            ->findOrFail($id);

        $now = CarbonImmutable::now();
        $huntingStartDate = CarbonImmutable::parse($hunting->start);

        // Check if user can cancel hunt
        if ($huntingStartDate->lessThanOrEqualTo($now)) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Nie można odwołać polowania!', 'message' => 'Nie można odwołać danego polowania, ponieważ polowanie się już zaczeło.'],
            ]);
        }

        $hunting->update(['canceled' => true]);

        return redirect()->route('hunting.index')->withAlertsSuccess([
            ['title' => 'Anulowano polowanie!', 'message' => 'Polowanie zostało anulowane prawidłowo.'],
        ]);
    }

    /**
     * Patch finish
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function finish(Request $request, $id): RedirectResponse
    {
        $hunting = HuntingBook::where('user_id', '=', $request->user()->id)
            ->where('canceled', '=', 0)
            ->findOrFail($id);

        $now = CarbonImmutable::now();
        $huntingStartDate = CarbonImmutable::parse($hunting->start);
        $huntingEndDate = CarbonImmutable::parse($hunting->end);

        if ($huntingStartDate->greaterThanOrEqualTo($now) && $huntingEndDate->lessThanOrEqualTo($now)) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Nie można zakończyć polowania!', 'message' => 'Nie można zakończyć danego polowania, ponieważ polowanie się jeszcze nie zaczeło lub już się zakończyło.'],
            ]);
        }

        $hunting->update(['end' => date(Helper::MYSQL_DATETIME_FORMAT, strtotime($now))]);

        return redirect()->route('hunting.edit', ['id' => $hunting->id])->withAlertsSuccess([
            ['title' => 'Zakończono polowanie!', 'message' => 'Polowanie zostało zakończone prawidłowo.'],
        ]);
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
        $hunting = HuntingBook::where('user_id', '=', $request->user()->id)
            ->where('canceled', '=', 0)
            ->findOrFail($id);

        $now = CarbonImmutable::now();
        $huntingEndDate = CarbonImmutable::parse($hunting->end);
        $huntingMaxEditDate = CarbonImmutable::parse($hunting->end)->add(1, 'day');

        if ($now->lessThanOrEqualTo($huntingEndDate) && $now->greaterThanOrEqualTo($huntingMaxEditDate)) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Nie można edytować polowania!', 'message' => 'Nie można edytować danego polowania, ponieważ upłynął czas na jego edycję.'],
            ]);
        }

        $animalsDB = Animal::all();

        $animalCategories = $animalsDB->filter(function ($item) {
            return data_get($item, 'parent_id') == null;
        })->all();
        $animals = $animalsDB->filter(function ($item) {
            return data_get($item, 'parent_id') != null;
        })->all();

        return view('hunting-book.edit')->withHunting($hunting)
            ->withAnimalCategories($animalCategories)
            ->withAnimals($animals);
    }

    /**
     * Patch edit
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'hunting_shots' => ['required', 'integer', 'min:0', 'max:100'],
            'hunting_description' => ['nullable', 'string', 'max:500'],

            'animal_id_database' => ['nullable', 'array'],
            'animal_category_id_stored' => ['nullable', 'array'],
            'animal_id_stored' => ['nullable', 'array'],
            'purpose_stored' => ['nullable', 'array'],
            'tag_stored' => ['nullable', 'array'],
            'weight_stored' => ['nullable', 'array'],

            'animal_id_database.*' => ['required_unless:animal_id_database,null', 'integer',
                Rule::exists(HuntedAnimal::class, 'id')->where(function ($query) use ($id) {
                    $query->where('hunting_book_id', '=', $id);
                }),
            ],
            'animal_category_id_stored.*' => ['required_unless:animal_id_database,null', 'integer',
                Rule::exists(Animal::class, 'id')->where(function ($query) {
                    $query->whereNull('parent_id');
                }),
            ],
            'animal_id_stored.*' => ['required_unless:animal_id_database,null', 'integer',
                Rule::exists(Animal::class, 'id')->where(function ($query) {
                    $query->whereNotNull('parent_id');
                }),
            ],
            'purpose_stored.*' => ['required_unless:animal_id_database,null',
                new EnumValue(HuntedAnimalPurposes::class, false)],
            'tag_stored.*' => ['required_unless:animal_id_database,null', 'string', 'max:100'],
            'weight_stored.*' => ['required_unless:animal_id_database,null', 'integer', 'min:0'],

            'animal_category_id' => ['nullable', 'array'],
            'animal_id' => ['nullable', 'array'],
            'purpose' => ['nullable', 'array'],
            'tag' => ['nullable', 'array'],
            'weight' => ['nullable', 'array'],

            'animal_category_id.*' => ['required_unless:animal_category_id,null', 'integer',
                Rule::exists(Animal::class, 'id')->where(function ($query) {
                    $query->whereNull('parent_id');
                }),
            ],
            'animal_id.*' => ['required_unless:animal_category_id,null', 'integer',
                Rule::exists(Animal::class, 'id')->where(function ($query) {
                    $query->whereNotNull('parent_id');
                }),
            ],
            'purpose.*' => ['required_unless:animal_category_id,null',
                new EnumValue(HuntedAnimalPurposes::class, false)],
            'tag.*' => ['required_unless:animal_category_id,null', 'string', 'max:100'],
            'weight.*' => ['required_unless:animal_category_id,null', 'integer', 'min:0'],
        ]);

        $hunting = HuntingBook::where('user_id', '=', $request->user()->id)
            ->where('canceled', '=', 0)
            ->findOrFail($id);

        $now = CarbonImmutable::now();
        $huntingEndDate = CarbonImmutable::parse($hunting->end);
        $huntingMaxEditDate = CarbonImmutable::parse($hunting->end)->add(1, 'day');

        if ($now->lessThanOrEqualTo($huntingEndDate) && $now->greaterThanOrEqualTo($huntingMaxEditDate)) {
            return redirect()->route('hunting.index')->withAlertsDanger([
                ['title' => 'Nie można edytować polowania!', 'message' => 'Nie można edytować danego polowania, ponieważ upłynął czas na jego edycję.'],
            ]);
        }

        // Save changes to hunting
        $hunting->forceFill([
            'shots' => $request->hunting_shots,
            'description' => $request->hunting_description,
        ])->save();

        // Get previous hunted animals into array
        $huntedAnimals = $hunting->huntedAnimals->pluck('id')->toArray();

        // Update hunted animals in database
        if (isset($request->animal_id_database)) {
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
        }

        // Delete hunted animals from database
        if (count($huntedAnimals) >= 1 && !isset($request->animal_id_database)) {
            foreach ($huntedAnimals as $deletedHuntedAnimal) {
                HuntedAnimal::find($deletedHuntedAnimal)->delete();
            }
        } elseif (isset($request->animal_id_database)) {
            $deletedHuntedAnimals = array_values(array_diff($huntedAnimals, $request->animal_id_database));
            foreach ($deletedHuntedAnimals as $deletedHuntedAnimal) {
                if (in_array($deletedHuntedAnimal, $huntedAnimals)) {
                    HuntedAnimal::find($deletedHuntedAnimal)->delete();
                }
            }
        }

        // Add hunted animals to database
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

        return redirect()->back()->withAlertsSuccess([
            ['title' => 'Zapisano!', 'message' => 'Dane zostały zapisane poprawnie.'],
        ]);
    }
}
