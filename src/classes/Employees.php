<?php

namespace App\Classes;

class Employees
{
    public function getAllEmployees($array) {

        $data = array();

        foreach ($array as $person) {
            $firstName = $person['imie'];
            $lastName = $person['nazwisko'];
            $address = $person['adres'];
            $pesel = $person['pesel'];

            $validator = new PeselValidator();
            $gender = $validator->getGender($pesel);
            $birthday = $validator->getBirthday($pesel);

            $data[] = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'address' => $address,
                'pesel' => $pesel,
                'gender' => $gender,
                'birthday' => $birthday
            ];
        }

        return $data;
    }
}