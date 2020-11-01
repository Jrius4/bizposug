@extends('layouts.app')
@section('content')
<div class="container">
<div class="row d-block align-items-center justify-content-center">
    <div class="col-md-10">
        <h4 class="text-center">cash inflows</h4>
        <table class="table table-sm table-borderless">
            <thead>
                <tr>
                    <th colspan="6">
                        <h4 class="text-center">Business Sheet </h4>
                    </th>
                </tr>

            </thead>

            <tbody>
                <tr>
                    <th colspan="6">
                        <h4 class="text-center">Assets</h4>
                    </th>
                </tr>
                <tr>
                    <th colspan="6">
                        Current Assets:
                    </th>
                </tr>
                <tr>
                    <td></td>
                    <td>
                    Cash
                    </td>
                    <td style="width: 2rem">$</td>
                    <td>146000</td>
                    <td style="width: 2rem"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                    Accounts Receivable
                    </td>
                    <td style="width: 2rem"></td>
                    <td>(81000)</td>
                    <td style="width: 2rem"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                    Prepaid Expenses
                    </td>
                    <td style="width: 2rem"></td>
                     <td style="border-bottom: #004D40 2px solid;color:">45000</td>
                    <td style="width: 2rem">$</td>
                    <td>41500</td>
                </tr>
                <tr>
                    <th colspan="6">
                        Non-current Assets:
                    </th>
                </tr>
                <tr>
                    <td></td>
                    <td>
                    Property, Plant and Equipment
                    </td>
                    <td style="width: 2rem"></td>
                    <td></td>
                    <td style="border-bottom: #004D40 2px solid;width: 2rem"></td>
                    <td style="border-bottom: #004D40 2px solid;">145000</td>
                </tr>
                <tr>

                    <th colspan="2">
                    Total Assets
                    </th>
                    <td style="width: 2rem"></td>
                    <td></td>
                    <td style="border-bottom: #004D40 3px double;width: 2rem">$</td>
                    <td style="border-bottom: #004D40 3px double;">845000</td>
                </tr>
            </tbody>
            <tbody>
                <tr>
                    <th colspan="6">
                        <h5 class="text-center">LIABILITIES AND OWNER'S EQUITY</h5>
                    </th>
                </tr>
                <tr>
                    <td colspan="6">
                        Current Liabilities:
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                   Accounts Payable
                    </td>
                    <td style="width: 2rem">$</td>
                    <td>8400</td>
                    <td style="width: 2rem"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                    Accounts Payable
                    </td>
                    <td style="width: 2rem"></td>
                    <td style="border-bottom: #004D40 2px solid;">8000</td>
                    <td style="width: 2rem">$</td>
                    <td>16400</td>
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
                    <td style="border-bottom: #004D40 2px solid;width: 2rem">$</td>
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
                    <td style="border-bottom: #004D40 2px solid;width: 2rem">$</td>
                    <td style="border-bottom: #004D40 2px solid;">13000</td>
                </tr>
                <tr>
                   <td colspan="2">
                    Cash – December 31, 2019
                    </td>
                    <td style="width: 2rem"></td>
                    <td style="border-bottom: #004D40 4px double;"></td>
                    <td style="border-bottom: #004D40 4px double;width: 2rem"></td>
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
