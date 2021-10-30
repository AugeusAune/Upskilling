@extends('layout_exports.history')

@section('content')

<div class="container mt-5">
    <h4 class="text-center">History Data</h4>
    <hr>
    <div class="d-flex justify-content-between align-items-center">
        <div class="right">
            <p>Exported At : {{ $time }}</p>
            <p>Exported By : {{ Auth::user()->name }}</p>
        </div>
    </div>
    <div class="table-responsive p-0">
        <table class="table align-items-center mb-0">
          <thead>
            <tr>
              <th class="text-uppercase font-weight-bolder opacity-7 align-middle text-center">No</th>
              <th class="text-uppercase font-weight-bolder opacity-7">Cashier</th>
              <th class="text-uppercase font-weight-bolder opacity-7 ps-2">Orders</th>
              <th class="text-uppercase font-weight-bolder opacity-7 ps-2">Price</th>
              <th class="text-center text-uppercase font-weight-bolder opacity-7">Date</th>
            </tr>
          </thead>
          <tbody>
        <?php $i = 1 ?>
        @forelse ($histories as $item)
        <tr class="align-items-center">
            <td class="align-items-center text-center" style="font-size: 12px">{{ $i++ }}</td>
            <td>
                <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center">
                        <p class="text-small mb-0" style="font-size: 12px">{{ $item->user->name }}</p>
                    </div>
                </div>
            </td>
            <td>
                @foreach (json_decode($item->orders) as $orders)
                    <p class="text-sm mb-0" style="font-size: 12px margin:0 20px">{{ $orders->amount }} {{ $orders->name }}</p>
                @endforeach
            </td>
            <td>
                <span class="text-small" style="font-size: 12px">Rp. {{ number_format(array_reduce( array_map(fn($el) => $el->amount * $el->price, json_decode($item->orders)), fn($a, $b) => $a + $b)) }}</span>
            </td>
            <td class="align-middle text-center">
                <span class="text-xs" style="font-size: 12px">{{ date('d F Y', strtotime($item->created_at)) }}</span>
            </td>
        </tr>
        @empty
            <tr class="text-center middle">
            <td>
                empty
            </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>

@endsection