<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Card;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CardCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Card::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required', 'string', Rule::in(['Visa', 'MasterCard', 'American Express', 'Discover Card', 'Visa Retired', 'JCB']),
            ],
        ];
    }
}
