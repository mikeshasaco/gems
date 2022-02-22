<?php

function pre($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    exit;
}

function pr($data){
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function generate_random_string($length = 10){
	//to create unique id as per length
	$pass = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, $length);
	return $pass;
}

function calculate_grade ($score){
    if($score >= 90 && $score <= 93){
        return 'A-';
    }else if($score >= 94 && $score <= 97){
        return 'A';
    }else if($score >= 98 && $score <= 100){
        return 'A+';
    }
    else{
        return '-';
    }
}