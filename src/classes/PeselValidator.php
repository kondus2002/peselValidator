<?php

namespace App\Classes;

use http\Exception\RuntimeException;

class PeselValidator
{
    public function validatePesel($pesel){

        if (!preg_match('/^[0-9]+$/', $pesel)) {
            return false;
        }

        $peselString = (string) $pesel;
        if (strlen($peselString)!=11) {
            return false;
        }

        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        $characters = str_split($pesel);
        $peselArray = array();

        foreach($characters as $character) {
            $peselArray[] = $character;
        }

        $sum = 0;

        for($i=0; $i<10; $i++){

            $peselNumber = $characters[$i];
            $weightNumber = $weights[$i];

            if($peselNumber*$weightNumber>9){
                $sum += ($peselNumber*$weightNumber)%10;
            } else {
                $sum += $peselNumber*$weightNumber;
            }
        }

        if($sum>9){
            $sum = 10 - $sum%10;
        } else {
            $sum = 10 - $sum;
        }

        if ($sum==$peselArray[10]){
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }

    public function getGender($pesel) {

        $characters = str_split($pesel);
        $genderNumber = $characters[9];

        if($genderNumber %2 === 0){
            $gender = "Kobieta";
        } else {
            $gender = "Mężczyzna";
        }

        return $gender;
    }

    public function getBirthday($pesel) {

        $characters = str_split($pesel);

        $yearCode = $characters[0].$characters[1];
        $monthCode = $characters[2].$characters[3];
        $dayCode = $characters[4].$characters[5];

        if($monthCode > 80 && $monthCode < 93) {
            $century = 18;
        } else if ($monthCode > 0 && $monthCode < 13) {
            $century = 19;
        } else if ($monthCode > 20 && $monthCode < 33) {
            $century = 20;
        } else if ($monthCode > 40 && $monthCode < 53) {
            $century = 21;
        } else {
            $century = 22;
        }

        switch ($monthCode) {
            case 81:
            case 01:
            case 21:
            case 41:
            case 61:
                $month = '01';
                break;

            case 82:
            case 02:
            case 22:
            case 42:
            case 62:
                $month = '02';
                break;

            case 83:
            case 03:
            case 23:
            case 43:
            case 63:
                $month = '03';
                break;

            case 84:
            case 04:
            case 24:
            case 44:
            case 64:
                $month = '04';
                break;

            case 85:
            case 05:
            case 25:
            case 45:
            case 65:
                $month = '05';
                break;

            case 86:
            case 06:
            case 26:
            case 46:
            case 66:
                $month = '06';
                break;

            case 87:
            case 07:
            case 27:
            case 47:
            case 67:
                $month = '07';
                break;

            case 88:
            case '08':
            case 28:
            case 48:
            case 68:
                $month = '08';
                break;

            case 89:
            case '09':
            case 29:
            case 49:
            case 69:
                $month = '09';
                break;

            case 90:
            case 10:
            case 30:
            case 50:
            case 70:
                $month = '10';
                break;

            case 91:
            case 11:
            case 31:
            case 51:
            case 71:
                $month = '11';
                break;

            case 92:
            case 12:
            case 32:
            case 52:
            case 72:
                $month = '12';
                break;
        }

        return "$dayCode.$month.$century$yearCode";
    }
}