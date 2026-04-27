<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    public function getOrderItem(){
        $order = Order::where('user_id',Auth::user()->id)->first();
        $orderItems = OrderItem::where('order_id',$order->id)->get();
        $total = OrderItem::where('order_id', $order->id)
            ->sum(DB::raw('price * quantity'));
        return response()->json([
            'orderItems' => $orderItems,
            'total' => $total
        ]);
    }

    // public function setCart(Request $request){
    //     $user = Auth::user();
    //     $order = Order::firstOrCreate(
    //         ['user_id' => $user->id],
    //         ['total' => 0]
    //     );
    //     $item = OrderItem::where('order_id', $order->id)
    //                  ->where('product_id', $request->product_id)
    //                  ->first();

    //     if ($item) {
    //             $item->quantity += $request->quantity;
    //             $item->save();
    //         } else {
    //             OrderItem::create([
    //                 'order_id' => $order->id,
    //                 'product_id' => $request->product_id,
    //                 'title' => $request->title,
    //                 'price' => $request->price,
    //                 'quantity' => $request->quantity,
    //                 'image' => $request->image ?? null
    //             ]);
    //         }
    //         $total = OrderItem::where('order_id', $order->id)
    //         ->sum(DB::raw('price * quantity'));

    //         $order->update([
    //             'total' => $total
    //         ]);

    //     return response()->json([
    //         'massage' => 'ok'
    //     ]);
    // }

    public function updateOrderItem(Request $request,string $id){
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->first();
        $orderItems = OrderItem::where('order_id', $order->id)
                            ->where('product_id',$id)->first();
        if($orderItems){
            $orderItems->quantity += $request->value;
            $orderItems->save();
        }
        $total = OrderItem::where('order_id', $order->id)
            ->sum(DB::raw('price * quantity'));

            $order->update([
                'total' => $total
            ]);
        return response()->json([
            'massage' => 'ok'
        ]);
    }

    public function deleteOrderItem(string $id){
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->first();
        $orderItems = OrderItem::where('order_id', $order->id)
                            ->where('product_id',$id)->first();
        if($orderItems){
            $orderItems->delete();
        }
        $total = OrderItem::where('order_id', $order->id)
            ->sum(DB::raw('price * quantity'));

            $order->update([
                'total' => $total
            ]);
        return response()->json([
            'massage' => 'ok'
        ]);
    }
}
