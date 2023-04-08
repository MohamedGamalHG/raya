<?php


namespace App\Http\Controllers;


use App\Http\Requests\OrderRequest;
use App\Mail\MarchentMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        $product = Product::findOrFail($request->product_id);
        try {

            $arr = array();
            $arr['beaf'] = $request->quantity * .150;
            $arr['cheese'] = $request->quantity * .030;
            $arr['onion'] = $request->quantity * .020;

            $stock = Stock::first();

            /**
             * @param $stock,$arr
             * @returns boolean
            /*
             *  this function to check if after we subtract the amount that coming it will be more than that we have
                and if not it will update the amount in stock
            */
            $check = $this->checkAndUpdateStock($stock,$arr);

            if(!$check)
                return response()->json(['error'=>'Sorry For Now You Can Make Order In Another Time']);

            /**
             * @param $stock
            /*
             *  this function is send mail that we need more ingredients and update the status in stock
             */
            $this->sendIngredientMail($stock);

            $order = Order::create([
                'quantity' => $request->quantity,
                'product_id' => $request->product_id
            ]);

            return response()->json(['success'=>'Order Has Add Successfully']);
        }catch (\Exception $e)
        {
            return response()->json(['error'=>'Something Went Wrong']);
        }

    }
    private function checkAndUpdateStock($stock,$arr)
    {
        $after_subtract_b = $stock['qty_beaf']- $arr['beaf'];
        $after_subtract_c = $stock['qty_cheese']- $arr['cheese'];
        $after_subtract_o = $stock['qty_onion']- $arr['onion'];

        if($after_subtract_b < 0  || $after_subtract_c < 0 || $after_subtract_o < 0)
            return false;

        $update_stock = Stock::find(1);

        $update_stock->update([
            'qty_beaf' => $after_subtract_b,
            'qty_cheese' => $after_subtract_c,
            'qty_onion' => $after_subtract_o
        ]);
        return  true;
    }
    private function sendIngredientMail($stock)
    {
        $half_beaf = ($stock['real_beaf'] / 2);
        $half_cheese = ($stock['real_cheese'] / 2);
        $half_onion = ($stock['real_onion'] / 2);

        $update_stock = Stock::find(1);
        $st = new Stock();

        // so we can do that if we need if it come to 50%
        // if ( ($stock['qty_beaf'] == $half_beaf || $half_beaf > $stock['qty_beaf']) )
        if ( $half_beaf > $stock['qty_beaf'] ) {
            if($stock['send_mail_beaf'] == 0) {
                $st->send_mail_beaf = 1;
                $st->save();
                Mail::to('tm@gmail.com')->send(new MarchentMail());
            }
        }
        // ($stock['qty_cheese'] == $half_cheese || $half_cheese > $stock['qty_cheese'])
        else if (  $half_cheese > $stock['qty_cheese'] ) {
            if($stock['send_mail_cheese'] == 0) {
                $st->send_mail_cheese = 1;
                $st->save();
                Mail::to('tm@gmail.com')->send(new MarchentMail());
            }
        }
        // ($stock['qty_onion'] == $half_onion || $half_onion > $stock['qty_onion'])
        else if (  $half_onion > $stock['qty_onion'] ){
            if($stock['send_mail_onion'] == 0) {
                 $st->send_mail_onion = 1;
                 $st->save();
                Mail::to('tm@gmail.com')->send(new MarchentMail());
            }
        }
    }

}
