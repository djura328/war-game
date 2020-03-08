<?php
namespace App\Modules;

class Log{

    private $messages = [];

    public function __construct(){
        //dump('log');
    }

    public function addLog ($data) {

        foreach ($data as $key => $item) {

            $mesage = '';
            $mesage .= $key .': "'. $item->name . '", with ' . $item->units_count . ' unites, ';

            if($key === 'Attacker') $mesage .=  ' attack with ' ;
            else if($key === 'Attacked') $mesage .=  ' defense with ' ;

            $mesage .=  '"'.$item->units->first()->name . '" ('.$item->units->first()->health.' health)';

            array_push($this->messages, $mesage);

        }

    }

    public function showLog(){
        return implode("<br>",$this->messages);
    }
}

?>
