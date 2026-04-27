<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OrderController extends Controller
{
    
    public function checkout(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|min:3|max:15',
            'secondName' => 'required|string|min:3|max:15',
            'email' => 'required|email',
            'password' => 'required|min:3',
            'dob' => 'required|date',
            'phoneNumber' => 'required',
            'address' => 'required|string',
            'cart' => 'required|array'
        ]);

        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'firstName' => $request->firstName,
                'secondName' => $request->secondName,
                'password' => Hash::make($request->password),
                'dob' => $request->dob,
                'phoneNumber' => $request->phoneNumber,
                'address' => $request->address,
            ]
        );
        if(!$user->hasRole('User')){
            $user->assignRole('User');
        }

        Auth::login($user);

            $order = Order::firstOrCreate(
                ['user_id' => Auth::user()->id],
                ['total' => 0],
            );


            foreach ($request->cart as $item) {
                $itemExist = OrderItem::where('order_id', $order->id)
                     ->where('product_id', $item['product_id'])
                     ->first();

                if ($itemExist) {
                    $itemExist->quantity += $item['quantity'];
                    $itemExist->save();
                } else {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'title' => $item['title'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'image' => $item['image'] ?? null
                    ]);
                }
            }

            $total = OrderItem::where('order_id', $order->id)
            ->sum(DB::raw('price * quantity'));

            $order->update([
                'total' => $total
            ]);
            return response()->json([
                'massage' => 'ok',
            ]);
        }

    }

