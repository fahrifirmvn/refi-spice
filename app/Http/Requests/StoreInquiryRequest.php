<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:254'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company_name' => ['required', 'string', 'max:150'],
            'destination_country' => ['required', 'string', 'max:150'],
            'commodity_interest' => ['required', 'string', 'max:150', Rule::in(Product::query()->active()->pluck('name'))],
            'volume_estimate' => ['required', 'string', Rule::in(['1-5 MT', '5-20 MT', '20+ MT'])],
            'additional_notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi agar tim kami dapat menghubungi Anda.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'phone.max' => 'Nomor telepon maksimal 50 karakter.',
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'destination_country.required' => 'Negara tujuan wajib diisi.',
            'commodity_interest.required' => 'Komoditas yang diminati wajib dipilih.',
            'commodity_interest.in' => 'Komoditas yang dipilih tidak valid.',
            'volume_estimate.required' => 'Estimasi volume wajib dipilih.',
            'volume_estimate.in' => 'Estimasi volume yang dipilih tidak valid.',
        ];
    }
}
