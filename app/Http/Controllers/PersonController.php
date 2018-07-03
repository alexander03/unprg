<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Http\Requests;
use App\Person;
use App\Librerias\Libreria;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PersonController extends Controller
{
    public function employeesautocompleting($searching)
    {
        $entidad    = 'Person';
        $mdlPerson = new Person();
        $resultado = Person::where(function($query){    
                                $query->where('type', '=', 'E');
                            })->where(function($query) use ($searching){    
                                $query->where('lastname', 'LIKE', '%'.$searching.'%')->orwhere('firstname', 'LIKE', '%'.$searching.'%');
                            });
        $list      = $resultado->get();
        $data = array();
        foreach ($list as $key => $value) {
            $data[] = array(
                            'label' => $value->lastname.' '.$value->firstname,
                            'id'    => $value->id,
                            'value' => $value->lastname.' '.$value->firstname,
                        );
        }
        return json_encode($data);
    }

    public function providersautocompleting($searching)
    {
        $entidad   = 'Person';
        $mdlPerson = new Person();
        $resultado = Person::where('type', '=', 'P')->where('bussinesname', 'LIKE', '%'.$searching.'%');
        $lista     = $resultado->get();
        $data      = array();
        foreach ($lista as $key => $value) {
            $data[] = array(
                            'label' => $value->bussinesname,
                            'id'    => $value->id,
                            'value' => $value->bussinesname,
                        );
            
        }
        return json_encode($data);
    }

    public function customersautocompleting($searching)
    {
        $entidad    = 'Person';
        $mdlPersona = new Person();
        $resultado  = Person::where('type', '=', 'C')->where('bussinesname', 'LIKE', '%'.strtoupper($searching).'%')->orWhere(DB::raw("CONCAT(lastname,' ',firstname)"),'LIKE' ,'%'.strtoupper($searching).'%');
        $lista      = $resultado->get();
        $data       = array();
        foreach ($lista as $key => $value) {
            $customername = "";
            if ($value->secondtype == 'C') {
                $customername = $value->bussinesname;
            }elseif ($value->secondtype == 'P') {
                $customername = $value->lastname." ".$value->firstname;
            }
            $data[] = array(
                            'label' => $customername,
                            'id'    => $value->id,
                            'value' => $customername,
                        );
        }
        return json_encode($data);
    }
}
