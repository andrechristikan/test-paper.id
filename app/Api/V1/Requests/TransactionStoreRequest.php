<?php

namespace App\Api\V1\Requests;

use Config;
use Dingo\Api\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
{
    public function rules()
    {
        return Config::get('boilerplate.transaction-store.validation_rules');
    }

    public function authorize()
    {
        return true;
    }
}
