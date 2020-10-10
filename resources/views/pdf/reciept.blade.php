<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="stylesheet" media="screen"  href="{{asset('/print/main.css')}}">
        {{-- <link rel="stylesheet"  media="print" href="{{asset('/print/reciept.css')}}"> --}}

<style>
    .barcode{
        display: block;
        justify-items: center;

    }
    .barcode .bars{
        margin: auto;
        width: 4cm
    }
.page-break {
    page-break-after: always;
}
table {
        border-collapse: collapse;
        width: 80%;
        font-size: 1pt;
        margin: auto
    }
    td,
    th {
        border: 0.2px solid transparent;
        padding: 1pt;
        text-align: left;
        font-size: xx-small;
    }
    .header{
        margin-right: 8pt;
    }


    @page :left {
        margin-right: 8pt;
    }

    @page :right {
        margin-left: 8pt;
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
                    <small>No.+256 779 571619 <br> Email. kazibwejuliusjunior@gmail.com <br> Address. Muyenga A <br>Date.{{$trans->created_at->format('D, d/M/Y H:i:s')}}</small>
                    </p>
                </div>

                <section>
                    <table>
                        <thead>
                            <tr>
                            <td>Name</td>
                            <td>Brand</td>
                            <td>Size</td>
                            <td>QTY</td>
                            <td>Rate <sup>UGX</sup></td>
                            <td>Amount <sup>UGX</sup></td>
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
                        <tbody>
                            <tr>
                                <td>Type of Order</td>
                                <td colspan="5">{{$trans->type_of_transaction}}</td>
                            </tr>
                            <tr>
                                <td>Subtotal <sub>UGX</sub></td>
                                <td colspan="5">{{number_format($trans->subtotal)}}</td>
                            </tr>
                            <tr>
                                <td>Discount <sub>UGX</sub></td>
                                <td colspan="5">{{number_format($trans->discount)}}</td>
                            </tr>
                            <tr>
                                <td>Total Amount <sub>UGX</sub></td>
                                <td colspan="5">{{number_format($trans->total)}}</td>
                            </tr>

                        </tbody>

                    </table>
                </section>
                <div class="clearfix"></div>
                <section class="barcode">
                    <div class="bars">
                         {!! DNS1D::getBarcodeHTML($trans->code, 'UPCA')!!}

                         <span>{{"0".strval($trans->code)."1"}}</span>
                    </div>

                </section>


            </div>
            {{-- <div class="page-break"></div> --}}
        </main>

    </body>
</html>


