

<style>
    .invoice-header{
        width: 208px;
        margin: 0 auto;
        text-align: center;
        padding: 20px 0;
    }
    .invoice-header p{
        margin: 0 auto;
    }
    .ticket table{
        width: 208px;
        margin: 0 auto !important;
    }
    .company_name{
        width: 190px;
        font-size: 10px;
        margin: 0 auto;
        text-align: center;
        padding: 20px 0;
    }
    .ticket table thead tr th{
        text-align: left !important;
    }
    .ticket p, th, td{
        color: #000000;
        font-weight: bold!important;
        font-size: 11px;
    }

</style>
<div style="margin-left: -10px" class="ticket">
    <div class="invoice-header">
        <img style="width: 140px; margin: 0 !important;" src="{{ $logo }}">
        <br/>
        <p>{{ $project_name }}</p>
        <p>{{ $branch->name }}</p>
        <p>{{ $branch->address }}</p>
        <p>Vat REG. No :{{ $vat_reg_no }}</p>
        <p>Date : {{ $date }} &nbsp; Time : {{ $time }}</p>
        <p>Bill No : {{ $sellDetails->sale_code }}</p>
    </div>
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Qty</th>
            <th>AMT</th>
        </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->unit_price }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->sub_total }}</td>
            </tr>
            <tr>
                <td style="font-size: 10px !important;">VAT+SC+OH</td>
                <td>{{ $product->vat_sc_oh }}</td>
                <td></td>
                <td>{{ $product->quantity*$product->vat_sc_oh }}</td>
            </tr>
            <tr>
                <td colspan="4">
                    <hr style="border-bottom: 1px solid #000; margin: 0 auto" />
                </td>
            </tr>
        @empty
            <tr>
                <td>No Product Sale</td>
            </tr>
        @endforelse

        </tbody>
    </table>
    <table>
        <thead>
        <tr>
            <th style="width: 45px">Total Sales:</th>
            <th>{{ $sellDetails->total_bill }} </th>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000;"></td>
        </tr> 
        <tr>
            <th style="width: 80px">Total <span style="font-size: 10px !important;">(VAT+SC+OH):</span></th>
            <td>{{ $sellDetails->total_vat_sc_oh_amount }} </td>
        </tr>
        <tr>
            <td style="border-top: 1px solid #000;"></td>
        </tr>
        <tr>
            <th style="width: 45px">Total Bill:</th>
            <th>{{ $sellDetails->in_total_bill }}  </th>
        </tr>
        </thead>
    </table>
    <p class="company_name">Developed and Maintained By <br/> Skies Engineering and Technologies Ltd.<br><i>www.setcolbd.com</i></p>
</div>

