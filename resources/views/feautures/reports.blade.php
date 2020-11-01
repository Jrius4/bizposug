@extends('layouts.app')
@section('content')
<div class="row d-flex justify-content-center">
    <div class="card col-md-2 p-0 m-2 financial">
        <div class="card-body p-0">
            <ul class="list-group">
                <li class="list-group-item teal">Financial Reports</li>
                <li class="list-group-item"><a href="{{route('reports.financial.cash-inflow')}}" class="nav-link">Cash Flow</a></li>
                <li class="list-group-item"><a href="{{route('reports.financial.balance-sheet')}}" class="nav-link">Income Statement</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Balance Sheet</a></li>
            </ul>
        </div>
    </div>

    <div class="card col-md-2 p-0 m-2">
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item teal"> Graphical Reports</li>
                <li class="list-group-item"><a href="{{route('sales.categories.summary.graphical')}}" class="nav-link">Product Categories</a></li>
                <li class="list-group-item"><a href="{{route('sales.summary.graphical')}}" class="nav-link">Products</a></li>
                <li class="list-group-item"><a href="{{route('payments.summary.graphical')}}" class="nav-link">Payments</a></li>
                <li class="list-group-item"><a href="{{route('transactions.summary.graphical')}}" class="nav-link">Transactions</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Customers</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Suppliers</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Workers</a></li>
            </ul>
        </div>
    </div>
    <div class="card col-md-2 p-0 m-2">
        <div class="card-body p-0">
            <ul class="list-group">
                <li class="list-group-item teal"> Summary Reports</li>
                <li class="list-group-item"><a href="" class="nav-link">Product Categories</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Products</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Payments</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Transactions</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Customers</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Suppliers</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Workers</a></li>
            </ul>
        </div>
    </div>
    <div class="card col-md-2 p-0 m-2">
        <div class="card-body p-0">
            <ul class="list-group">
                <li class="list-group-item teal"> Detailed Reports</li>
                <li class="list-group-item"><a href="" class="nav-link">Product Categories</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Products</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Payments</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Transactions</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Customers</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Suppliers</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Workers</a></li>
            </ul>
        </div>
    </div>
    <div class="card col-md-2 p-0 m-2 invertory">
        <div class="card-body p-0">
            <ul class="list-group">
                <li class="list-group-item teal"> Inventory Reports</li>
                <li class="list-group-item"><a href="" class="nav-link">Low Inventory</a></li>
                <li class="list-group-item"><a href="" class="nav-link">Inventory Summary</a></li>
            </ul>
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
