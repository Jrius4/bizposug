@extends('layouts.app')
@section('content')
<div class="container">
<div class="row d-block align-items-center justify-content-center">
    <div class="col-md-10">

        <table class="table table-sm table-borderless">
            <thead>
                 <thead>
                <tr>
                    <th colspan="6">
                        <h4 class="text-center">Business Cash Flow </h4>
                    </th>
                </tr>

            </thead>
            </thead>

            <tbody>
                <tr>
                    <th colspan="6">
                        Cash Flow From Operating Activities:
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                    Cash received from customers
                    </td>
                    <td style="width: 2rem">$</td>
                    <td>146000</td>
                    <td style="width: 2rem"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                    Cash paid for expenses
                    </td>
                    <td style="width: 2rem"></td>
                    <td>(81000)</td>
                    <td style="width: 2rem"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                    Cash paid to suppliers
                    </td>
                    <td style="width: 2rem"></td>
                    <td style="color: #C62828">(47500)</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                    Cash paid to workers
                    </td>
                    <td style="width: 2rem"></td>
                    <td style="border-bottom: #004D40 2px solid;color: #C62828">(0)</td>
                    <td style="border-bottom: #004D40 2px solid;" style="width: 2rem">$</td>
                    <td style="border-bottom: #004D40 2px solid;"></td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <th colspan="6">
                       Cash Flow from Investing Activities:
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                    Cash paid to acquire additional equipment
                    </td>
                    <td style="width: 2rem"></td>
                    <td></td>
                    <td style="width: 2rem"></td>
                    <td style="color: #C62828">(20300)</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <th colspan="6">
                       Cash received from investment of owner
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                    Cash received from investment of owner
                    </td>
                    <td style="width: 2rem">$</td>
                    <td>10000</td>
                    <td style="width: 2rem"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                    Cash received from bank loan
                    </td>
                    <td style="width: 2rem"></td>
                    <td>50000</td>
                    <td style="width: 2rem"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                    Cash paid for bank loan – partial payment
                    </td>
                    <td style="width: 2rem"></td>
                    <td style="color: #C62828">(27000)</td>
                    <td style="width: 2rem"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                    Cash paid to owner – withdrawal
                    </td>
                    <td style="width: 2rem"></td>
                    <td style="border-bottom: #004D40 2px solid;color: #C62828;">(20000)</td>
                    <td style="border-bottom: #004D40 2px solid;" style="width: 2rem">$</td>
                    <td style="border-bottom: #004D40 2px solid;">13000</td>
                </tr>
            </tbody>
            <tfoot>



                <tr>
                    <td colspan="2">
                    Net Increase (Decrease) in Cash for the Year
                    </td>
                    <td style="width: 2rem"></td>
                    <td></td>
                    <td style="width: 2rem">$</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2">
                    Add: Cash – January 1, 2019
                    </td>
                    <td style="width: 2rem"></td>
                    <td style="border-bottom: #004D40 2px solid;"></td>
                    <td style="border-bottom: #004D40 2px solid;" style="width: 2rem">$</td>
                    <td style="border-bottom: #004D40 2px solid;">13000</td>
                </tr>
                <tr>
                   <td colspan="2">
                    Cash – December 31, 2019
                    </td>
                    <td style="width: 2rem"></td>
                    <td style="border-bottom: #004D40 4px double;"></td>
                    <td style="border-bottom: #004D40 4px double;" style="width: 2rem"></td>
                    <td style="border-bottom: #004D40 4px double;">13000</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</div>


@endsection
@section('styles')
<style>

    .teal{
        background-color: teal;
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: 500;
    }
    .card{
        border: 3px teal solid !important;
        border-radius: 3% 3%;
    }
    .invertory{
        height: 200px;
    }
    .financial{
        height: 265px;
    }
    .nav-link{
        color:#004D40;
    }
</style>

@endsection
