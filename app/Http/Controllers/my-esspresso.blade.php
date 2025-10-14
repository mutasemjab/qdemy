<?php

namespace App\Http\Controllers\Web;

class Ingrediantes {

    public $hazelnut        = 'بندق';
    public $camel_milk      = 'لبن ناقة';
    public $stevia_sugar    = 'ستيفيا اخضر';
    public $espresso_coffee = 'بن اسبريسو';
    public $cocoa_butter    = 'زبدة كاكاو';

    private function prepareSteviaSugar()  {
       $this->stevia_sugar =  $this->stevia_sugar . 'منقي ';
       $this->stevia_sugar =  $this->stevia_sugar . ' ومفلتر';
       return $this->stevia_sugar;
    }

    protected function sendIngrediantesToMix() {
        return [
            $this->hazelnut,
            $this->camel_milk,
            $this->prepareSteviaSugar(),
            $this->espresso_coffee,
            $this->cocoa_butter,
        ];
    }

    public function getIngrediantes()  {
        return $this->hazelnut .' - '. $this->camel_milk .' - '. $this->stevia_sugar .' - '. $this->espresso_coffee .' - '. $this->cocoa_butter;
    }

}

class Mix {

    public $ingrediantes;
    public $essresso;

    public function __construct(Ingrediantes $ingrediantes) {
        $this->ingrediantes = $ingrediantes;
    }
    public function mix()  {
        return implode(' + ',$this->getIngrediantes());
    }

    public function getEsspresso()  {
        $this->essresso = $this->ingrediantes->mix();
        dd($this->essresso);
    }

}


class Wrapping {

    public $ingrediantes;
    public $label;

    public function __construct(Ingrediantes $ingrediantes) {
        $this->ingrediantes = $ingrediantes;
    }

    public function printIngrediantes()  {
        $this->label = $this->getIngrediantes->mix();
        dd($this->label);
    }
}

$ingrediantes = new Ingrediantes;
$mix          = new Mix;
$wrapping     = new Wrapping;

$ingrediantes->printIngrediantes();

?>
