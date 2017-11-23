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

        if ($model == null || $value == null || $check = null)
            return "false";

        $model = $model::where($property, $value)->get();

        if ($model->count() <= 0)
            throw (new ModelNotFoundException)->setModel($request->get('model'), [$property, $value]);
        else if ($model->count() > 1)
            throw new TooManyModelsException($property, $value);
        else
            $model = $model->first();

        return $user->can($check, $model) ? "true" : "false";
    }
}
