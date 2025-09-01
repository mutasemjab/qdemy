<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Or check for specific permissions
        // return auth()->user()->can('category-edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'required|boolean',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name_ar.required' => __('messages.name_ar_required'),
            'name_ar.max' => __('messages.name_ar_max'),
            'name_en.max' => __('messages.name_en_max'),
            'icon.max' => __('messages.icon_max'),
            'color.regex' => __('messages.color_invalid_format'),
            'is_active.required' => __('messages.status_required'),
            'is_active.boolean' => __('messages.status_must_be_boolean'),
        ];
    }
}
