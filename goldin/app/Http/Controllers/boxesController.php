<?php

namespace App\Http\Controllers;
use App\Models\boxes;
use Illuminate\Http\Request;
use App\Models\weapons;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Exception;

class boxesController extends Controller
{
    public function index()
    {
        $boxes = boxes::get()->groupBy('box_name')->map->first();

        //comprobar que la caja sea daily
        $boxes = $boxes->filter(function ($box) {
            return $box->daily == false;
        });
        
        foreach ($boxes as $box) {
            $box->box_name = str_replace('_', ' ', $box->box_name);
        }
        
        return view('dashboard', ['boxes' => $boxes]);
    }
    
    public function openBox(Request $request)
    {
        $box_name = $request->route('box_name');
        $boxes = boxes::where('box_name', $box_name)->with('weapons')->get();
    
        $rarityOrder = ['mitic', 'legendary', 'epic', 'rare', 'commun'];
    
        $boxes = $this->showColors($boxes);
    
        $firstBox = $boxes->first();
        if ($firstBox) {
            $boxTitle = str_replace('_', ' ', $firstBox->box_name);
        } else {
            $boxTitle = 'No boxes found';
        }
        foreach ($boxes as $box) {
            $box->weapons = $box->weapons->sortBy(function ($weapon) use ($rarityOrder) {
                $weapon->appearance_percentage = $this->calculateAppearancePercentage($weapon);
                return array_search($weapon->rarity, $rarityOrder);
            });
            $box->weapons = $box->weapons->sortByDesc('price');
        }
    
        return view('boxes.openBox', ['boxes' => $boxes, 'boxTitle' => $boxTitle]);
    }
    
    public function showColors($boxes)
    {
        foreach ($boxes as $box) {
            foreach ($box->weapons as $weapon) {
                $weapon->color = $this->getColorForRarity($weapon->rarity);
            }
        }

        return $boxes;
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
        $box = boxes::where('box_name', $box_name)->with('weapons')->first();

        if (!$box) {
            return response()->json(['error' => 'No boxes found.'], 404);
        }

        if ($user->coins < $box->cost) {
            return response()->json(['error' => 'You do not have enough coins.'], 403);
        }
        
        $user->coins -= $box->cost;
        $user->save();
        
        $weapons = $box->weapons;
        $weapon = $this->getWeaponBasedOnPercentage($weapons);
        
        if (!$weapon) {
            return response()->json(['error' => 'No weapon found.'], 500);
        }
        
        $user->weapons()->attach($weapon->id, ['created_at' => now(), 'updated_at' => now()]);

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
            if (isset($basePercentages[$weapon->rarity])) {
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
            $rand -= $weapon->appearance_percentage;
            if ($rand <= 0) {
                return $weapon;
            }
        }
    
        return null;
    }
}