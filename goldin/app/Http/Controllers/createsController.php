<?php

namespace App\Http\Controllers;
use App\Models\creates;
use Illuminate\Http\Request;
use App\Models\weapons;

class createsController extends Controller
{
    public function index()
    {
        //mostrar totes les caixes agrupades per nom de caixa i agafar la primera que tenga precio
        $creates = creates::whereNotNull('cost')->get()->groupBy('box_name')->map->first();

        // Replace hyphens with spaces in create names
        foreach ($creates as $create) {
            $create->box_name = str_replace('-', ' ', $create->box_name);
        }

        return view('dashboard', ['creates' => $creates]);
    }

    public function openCreate(Request $request)
    {
        // Get the box_name from the URL
        $box_name = $request->route('box_name');
    
        // Get all creates with the same box_name and a cost
        $creates = creates::where('box_name', $box_name)->with('weapon')->get();
    
        // Define the order of rarities
        $rarityOrder = ['mitic', 'legendary', 'epic', 'raree', 'commun'];
    
        // Sort the creates by the rarity order
        $creates = $creates->sortBy(function ($create) use ($rarityOrder) {
            return array_search($create->weapon->rarity, $rarityOrder);
        });
    
        // Call showColors and pass the creates
        $creates = $this->showColors($creates);
    
        $firstCreateWithCost = $creates->firstWhere('cost', '!=', null);
        if ($firstCreateWithCost) {
            $createTitle = str_replace('-', ' ', $firstCreateWithCost->box_name);
        } else {
            // Handle the case where there are no creates with a cost
            $createTitle = 'No creates found';
        }

        // //llamar a calculateAppearancePercentage y pasarle el arma
        // foreach ($creates as $create) {
        //     $create->weapon->appearance_percentage = $this->calculateAppearancePercentage($create->weapon);
        // }

        //call the function of percentatge
        foreach ($creates as $create) {
            $create->weapon->appearance_percentage = $this->calculateAppearancePercentage($create->weapon);
        }
    
        return view('creates.openCreate', ['creates' => $creates, 'createTitle' => $createTitle]);
    }

    public function showColors($creates)
    {
        foreach ($creates as $create) {
            if ($create->weapon) {
                switch ($create->weapon->rarity) {
                    case 'mitic':
                        $create->color = 'rgba(255,0,0,0.8)';
                        break;
                    case 'legendary':
                        $create->color = 'rgba(255,165,0,0.8)';
                        break;
                    case 'epic':
                        $create->color = 'rgba(128,0,128,0.8)';
                        break;
                    case 'rare':
                        $create->color = 'rgba(0,0,255,0.8)';
                        break;
                    case 'commun':
                        $create->color = 'rgba(0,128,0,0.8)';
                        break;
                }
            }
        }
    
        return $creates;
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
        // Define Base Percentages
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
    