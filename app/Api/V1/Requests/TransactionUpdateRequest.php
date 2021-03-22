<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.transaction-update.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
