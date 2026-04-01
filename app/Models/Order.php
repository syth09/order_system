<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name', 'status', 'total_amount'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Tính tổng tiền (nâng cao)
    public function calculateTotal()
    {
        $this->total_amount = $this->items()->sum('subtotal');
        $this->save();
    }
}
