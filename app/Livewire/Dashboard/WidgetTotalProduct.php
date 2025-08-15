<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class WidgetTotalProduct extends Component
{
    public function render()
    {
        return view('livewire.dashboard.widget-total-product');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    #[Computed]
    public function product()
    {
        return Product::count();
    }
}
