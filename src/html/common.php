<?php

    function sanitize($before){
        $after = array();

        foreach($before as $key => $value){
            if(is_array($before[$key])){
                foreach($value as $k => $y) {
                    $after[$key][$k] = htmlspecialchars($y, ENT_QUOTES, 'UTF-8');
                }
            }else{
                $after[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
        return $after;
    }
?>