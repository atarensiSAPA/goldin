<?php

namespace App\Http\Controllers;
use App\Models\creates;
use Illuminate\Http\Request;
use App\Models\weapons;

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
    
        $creates = $creates->sortBy(function ($create) use ($rarityOrder) {
            return array_search($create->weapons->first()->rarity, $rarityOrder);
        });
    
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
}