<?php

class   signal_check
{
    public static function blue_signal($number) {
        if($number = ""){ ?>
                <option value="" selected>--選択して下さい--</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">-</option>
            </select>
        <?php }elseif($number = "0"){ ?>
                <option value="">--選択して下さい--</option>
                <option value="0" selected>0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">-</option>
            </select>
        <?php }elseif($number = "1"){ ?>
                <option value="">--選択して下さい--</option>
                <option value="0">0</option>
                <option value="1" selected>1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">-</option>
            </select>
            <?php }elseif($number = "2"){ ?>
                <option value="">--選択して下さい--</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2" selected>2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">-</option>
            </select>
            <?php }elseif($number = "3"){ ?>
                <option value="">--選択して下さい--</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3" selected>3</option>
                <option value="4">4</option>
                <option value="5">-</option>
            </select>
            <?php }elseif($number = "4"){ ?>
                <option value="">--選択して下さい--</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4" selected>4</option>
                <option value="5">-</option>
            </select>
        <?php }elseif($number = "5"){ ?>
                <option value="">--選択して下さい--</option>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5" selected>-</option>
            </select>
        <?php }
    } ?>






































?>