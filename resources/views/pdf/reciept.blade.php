<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" media="screen"  href="{{asset('/print/main.css')}}">
        <link rel="stylesheet"  media="print" href="{{asset('/print/reciept.css')}}">

<style>
.page-break {
    page-break-after: always;
}
</style>
    </head>
    <body>
        <main>
            <div class="content">
                <div class="header">
                    <img src="{{asset('/assets/icons/cart.png')}}" alt="">

                    <p>
                         {{ config('app.name', 'Laravel') }}<br/>
                    <small>phone number <br> email <br> address <br>{{$trans->created_at->format('D, d/M/Y H:i:s')}}</small>
                    </p>
                </div>

                <section>
                    <table>
                        <thead>
                            <tr>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Size</th>
                            <th>QTY</th>
                            <th>Rate <sup>UGX</sup></th>
                            <th>Amount <sup>UGX</sup></th>
                            </tr>
                        </thead>

                        @if ($trans != null)
                        <tbody>
                            @foreach ($trans->products as $product)
                            <tr>
                                <td>{{$product['name']}}</td>
                                <td>{{$product['brands'][0]['name']}}</td>
                                <td>{{$product['sizes'][0]['name']}}</td>
                                <td>{{$product['qty']}}</td>
                                @if ($trans->type_of_transaction == 'Whole Sale')
                                <td>{{number_format($product['wholesale_price'])}}</td>
                                <td>{{number_format($product['wholesale_price'] * $product['qty'])}}</td>
                                @elseif($trans->type_of_transaction == 'Retail Sale')
                                <td>{{number_format($product['retailsale_price'])}}</td>
                                <td>{{number_format($product['retailsale_price'] * $product['qty'])}}</td>
                                @endif

                            </tr>
                            @endforeach
                        </tbody>
                        @endif

                    </table>
                    <div class="my-2"></div>
                    <table>
                        <tbody>
                            <tr>
                                <th>Type of Order</th>
                                <td>{{$trans->type_of_transaction}}</td>
                            </tr>
                            <tr>
                                <th>Subtotal <sub>UGX</sub></th>
                                <td>{{number_format($trans->subtotal)}}</td>
                            </tr>
                            <tr>
                                <th>Discount <sub>UGX</sub></th>
                                <td>{{number_format($trans->discount)}}</td>
                            </tr>
                            <tr>
                                <th>Total Amount <sub>UGX</sub></th>
                                <td>{{number_format($trans->total)}}</td>
                            </tr>

                        </tbody>

                    </table>
                </section>
                <div class="clearfix"></div>
                <section>
                    <?php
                    echo DNS1D::getBarcodeHTML('4445645656', 'CODE11');
                    ?>
                </section>


            </div>
            {{-- <div class="page-break"></div> --}}
        </main>

    </body>
</html>


