<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Xendit\Configuration;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Configuration::setXenditKey("xnd_development_IbnUdoWXrRRHW3YqVpKiDnTDQE609tuzYuaMbipWuxly9zZxfdUG6RU5OYoSTy");
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $products = Product::all();
        return view('welcome',array("products"=>$products));
    }
    public function detail($id){
        $product = Product::find($id);

        return view('detail-product',$product);
    }
    public function payment(Request $request){
        $product = Product::find($request->id);

        $uuid = (string) Str::uuid();

        $apiInstance = new InvoiceApi();
        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => $uuid,
            'description' => $product->description,
            'amount' => $product->price,
            'currency' => 'IDR',
            "customer"=> array(
            "given_names"=> "John",
            "email"=> "johndoe@example.com"
            ),
            "success_redirect_url"=> "https://localhost/8000",
          "failure_redirect_url"=> "https://localhost/8000",
        ]);

        try {
            $result = $apiInstance->createInvoice($createInvoiceRequest);
                $order = new Order();
                $order->product_id = $product->id;
                $order->checkout_link = $result['invoice_url'];
                $order->external_id = $uuid;
                $order->status = "pending";
                $order->save();

        } catch (\Xendit\XenditSdkException $e) {
            
        }

    }
}
