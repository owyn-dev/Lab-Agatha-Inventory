<?php

declare(strict_types=1);

namespace App\Livewire\ManageProduct;

use App\Enums\VariantProduct;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

final class FormEditProduct extends Component
{
    use WithFileUploads;

    public Product $product;

    public $code = '';

    public $name = '';

    public $image;

    public $variant = '';

    public $price = '';

    public $expired_day = '';

    public function mount(Product $product): void
    {
        $this->product = $product;

        $this->code = $product->code;

        $this->name = $product->name;

        $this->image = null;

        $this->variant = $product->variant->value;

        $this->price = $product->price;

        $this->expired_day = $product->expired_day;
    }

    public function render()
    {
        return view('livewire.manage-product.form-edit-product');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    #[Computed]
    public function variantProduct()
    {
        return collect(VariantProduct::cases())->map(fn ($variant) => [
            'value' => $variant->value,
            'label' => $variant->label(),
        ])->toArray();
    }

    public function update(): void
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $fileName = $this->product->image;

            if ( ! empty($this->image) && $this->image !== $fileName) {
                if ( ! empty($fileName) && Storage::disk('public')->exists("images/{$fileName}")) {
                    Storage::disk('public')->delete("images/{$fileName}");
                }

                $fileName = microtime(true) . '.' . mb_strtolower($this->image->getClientOriginalExtension());
                $this->image->storeAs('images', $fileName, 'public');
            }

            $this->product->update([
                'code' => $this->code,
                'name' => $this->name,
                'image' => $fileName,
                'variant' => $this->variant,
                'price' => $this->price,
                'expired_day' => $this->expired_day,
            ]);

            DB::commit();

            $this->reset(['image']);
            $this->resetValidation();

            flash()->info('Data updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            if (Storage::disk('public')->exists("images/{$fileName}")) {
                Storage::disk('public')->delete("images/{$fileName}");
            }

            flash()->error('Failed to save data: ' . $e->getMessage());
        }
    }

    protected function rules()
    {
        return [
            'code' => ['required', Rule::unique('products', 'code')->ignore($this->product->id), 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'variant' => ['required', Rule::in(VariantProduct::values())],
            'price' => ['required', 'numeric', 'min:0'],
            'expired_day' => ['required', 'integer', 'min:0'],
        ];
    }
}
