<?php

namespace App\Http\Controllers;

use App\Exceptions\TooManyModelsException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AuthorizationController extends Controller {

    public function checkAuthorization(Request $request) {
        $user = $request->user();

        if ($user == null)
            return "false";

        $model = $request->get('model');
        $property = $request->get('property', 'id');
        $value = $request->get('value');
        $check = $request->get('check');

        if($check == null)
            return "false";

        $additional = $request->get('additional'); // Additional models to pass to the check (Model,Param,Query;)

        if(ends_with($model, "::class")){
            $model = str_replace("::class", "", $model);
            $model = get_class(new $model());
        } else {
            if ($model == null || $value == null)
                return "false";
            $model = $model::where($property, $value)->get();

            if ($model->count() <= 0)
                throw (new ModelNotFoundException)->setModel($request->get('model'), [$property, $value]);
            else if ($model->count() > 1)
                throw new TooManyModelsException($property, $value);
            else
                $model = $model->first();
        }

        $array = array();
        $array[] = $model;

        if($additional != null){
            foreach(explode(';', $additional) as $add){
                $exploded = explode(',', $add);
                if(sizeof($exploded) != 3){
                    continue;
                }
                $m = $exploded[0];
                $p = $exploded[1];
                $f = $exploded[2];

                $model = $m::where($p, $f)->first();
                if($model != null)
                    $array[] = $model;
            }
        }

        return $user->can($check, $array) ? "true" : "false";
    }
}
