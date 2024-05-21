<?php

namespace App\Http\Controllers;
use App\Models\creates;
use Illuminate\Http\Request;
use App\Models\weapons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Exception;

class createsController extends Controller
{
    public function index()
    {
        $creates = creates::get()->groupBy('box_name')->map->first();
    
        foreach ($creates as $create) {
            $create->box_name = str_replace('_', ' ', $create->box_name);
        }
    
        return view('dashboard', ['creates' => $creates]);
    }
    
    public function openCreate(Request $request)
    {
        $box_name = $request->route('box_name');
        $creates = creates::where('box_name', $box_name)->with('weapons')->get();
    
        $rarityOrder = ['mitic', 'legendary', 'epic', 'rare', 'commun'];
    
        $creates = $this->showColors($creates);
    
        $firstCreate = $creates->first();
        if ($firstCreate) {
            $createTitle = str_replace('_', ' ', $firstCreate->box_name);
        } else {
            $createTitle = 'No creates found';
        }
        foreach ($creates as $create) {
            $create->weapons = $create->weapons->sortBy(function ($weapon) use ($rarityOrder) {
                $weapon->appearance_percentage = $this->calculateAppearancePercentage($weapon);
                return array_search($weapon->rarity, $rarityOrder);
            });
        }
    
        return view('creates.openCreate', ['creates' => $creates, 'createTitle' => $createTitle]);
    }
    
    public function showColors($creates)
    {
        foreach ($creates as $create) {
            foreach ($create->weapons as $weapon) {
                $weapon->color = $this->getColorForRarity($weapon->rarity);
            }
        }

        return $creates;
    }
    
    public function getColorForRarity($rarity)
    {
        switch ($rarity) {
            case 'mitic':
                return 'rgba(255,0,0,0.8)';
            case 'legendary':
                return 'rgba(255,165,0,0.8)';
            case 'epic':
                return 'rgba(128,0,128,0.8)';
            case 'rare':
                return 'rgba(0,0,255,0.8)';
            case 'commun':
                return 'rgba(0,128,0,0.8)';
        }
    }

    public function calculateAppearancePercentage($weapon)
    {
                // // Define Base Percentages
        // $basePercentages = [
        //     'mitic' => 1,
        //     'legendary' => 5,
        //     'epic' => 15,
        //     'rare' => 30,
        //     'commun' => 49,
        // ];
    
        // // Get the total number of creates
        // $totalCreates = creates::count();
    
        // // Get the number of creates with the same weapon_id
        // $weaponCreates = creates::where('weapon_id', $weapon->id)->count();
    
        // // Get the base percentage for the weapon's rarity
        // $basePercentage = $basePercentages[$weapon->rarity] ?? 0;
    
        // // Get the total number of weapons in the same rarity category
        // $totalWeaponsInRarity = weapons::where('rarity', $weapon->rarity)->count();
    
        // // Calculate the appearance percentage
        // if ($totalWeaponsInRarity != 0) {
        //     $appearancePercentage = (($weaponCreates / $totalCreates) * ($basePercentage / $totalWeaponsInRarity)) * 100;
        // } else {
        //     $appearancePercentage = 0;
        // }
    
        // // Debugging: Return the calculated values
        // return [
        //     'appearancePercentage' => round($appearancePercentage, 4),
        //     'weaponCreates' => $weaponCreates,
        //     'totalCreates' => $totalCreates,
        //     'basePercentage' => $basePercentage,
        //     'totalWeaponsInRarity' => $totalWeaponsInRarity,
        // ];
        $basePercentages = [
            'mitic' => 1,
            'legendary' => 5,
            'epic' => 15,
            'rare' => 30,
            'commun' => 49,
        ];
    
        return $basePercentages[$weapon->rarity] ?? 'N/A';
    }



    public function ajaxOpenBox(Request $request)
    {
        $user = Auth::user();
        $box_name = $request->input('box_name');
        $create = creates::where('box_name', $box_name)->with('weapons')->first();
    
        if (!$create) {
            return response()->json(['error' => 'No creates found.'], 404);
        }
    
        if ($user->coins < $create->cost) {
            return response()->json(['error' => 'You do not have enough coins.'], 403);
        }
        
        $user->coins -= $create->cost;
        $user->save();
        
        $weapons = $create->weapons;
        $weapon = $this->getWeaponBasedOnPercentage($weapons);
        
        if (!$weapon) {
            return response()->json(['error' => 'No weapon found.'], 500);
        }
        
        $user->weapons()->attach($weapon->id);

        // Add 50 exp per box
        $user->addExperience(50);
    
        $color = $this->getColorForRarity($weapon->rarity);
        
        return response()->json(['weapon' => $weapon, 'coins' => $user->coins, 'color' => $color]);
    }
    
    private function getWeaponBasedOnPercentage($weapons)
    {
        $basePercentages = [
            'mitic' => 1,
            'legendary' => 5,
            'epic' => 15,
            'rare' => 30,
            'commun' => 49,
        ];
    
        $totalPercentage = 0;
        $mostCommonWeapon = null;
        $mostCommonWeaponPercentage = 0;
    
        foreach ($weapons as $weapon) {
            if (isset($basePercentages[$weapon->rarity]) && $weapon->units > 0) {
                $weapon->appearance_percentage = $basePercentages[$weapon->rarity];
                $totalPercentage += $weapon->appearance_percentage;
    
                if ($weapon->appearance_percentage > $mostCommonWeaponPercentage) {
                    $mostCommonWeapon = $weapon;
                    $mostCommonWeaponPercentage = $weapon->appearance_percentage;
                }
            }
        }
    
        if ($totalPercentage < 100 && $mostCommonWeapon) {
            $mostCommonWeapon->appearance_percentage += 100 - $totalPercentage;
        }
    
        $rand = mt_rand(1, 100);
    
        foreach ($weapons as $weapon) {
            if ($weapon->units > 0) {
                $rand -= $weapon->appearance_percentage;
                if ($rand <= 0) {
                    return $weapon;
                }
            }
        }
    
        return null;
    }
}