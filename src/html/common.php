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

    function dbconnect() {
        $dsn = 'mysql:dbname=self_monitoring;host=localhost;charset=utf8';
        $user = 'root';
        $password = '';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $dbh;
    }
?>