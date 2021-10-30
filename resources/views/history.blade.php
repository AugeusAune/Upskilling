<x-app-layout>

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Today's Income</p>
                    <h5 class="font-weight-bolder mb-0">
                      Rp.{{ $todayIncome ? number_format($todayIncome) : 'No Income'}}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">This Week Income</p>
                    <h5 class="font-weight-bolder mb-0">
                      Rp. {{ $thisWeekIncome ? number_format($thisWeekIncome) : 'No Income' }}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">This Month income</p>
                    <h5 class="font-weight-bolder mb-0">
                      Rp. {{ $thisMonthIncome ? number_format($thisMonthIncome) : 'No Income' }}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Last Month Income</p>
                    <h5 class="font-weight-bolder mb-0">
                      Rp. {{ $lastMonthIncome ? number_format($lastMonthIncome) : 'No Income'}}
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-lg-12 col-md-12 col-sm-6 col-12 d-flex align-items-center justify-content-between">
          <div class="left">
            <form action="" method="GET" id="formData">
              <input type="hidden" name="page" value="{{ request()->page }}">
              <select class="form-select" name="filter" aria-label="Default select example" style="padding-right: 35px" onchange="document.getElementById('formData').submit()">
                <option {{ request()->filter == 1 ? 'Selected' : '' }} value="1">All</option>
                <option {{ request()->filter == 2 ? 'Selected' : '' }} value="2">Today</option>
                <option {{ request()->filter == 3 ? 'Selected' : '' }} value="3">This Week</option>
                <option {{ request()->filter == 4 ? 'Selected' : '' }} value="4">This Month</option>
                <option {{ request()->filter == 5 ? 'Selected' : '' }} value="5">Last Month</option>
              </select>
            </form>
          </div>
          <div class="right d-flex">
            <form action="/preview" method="GET" class="mx-2">
              <input type="hidden" name="filter" value="{{ request()->filter }}">
              <button type="submit" class="btn btn-danger"><i class="fas fa-file-pdf"></i> Preview PDF</button>
            </form>
            <button type="button" class="btn btn-success"><i class="fas fa-file-excel"></i> Export Excel</button>
          </div>
        </div>
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>History</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 align-middle text-center">No</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Cashier</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Orders</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Price</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 1 ?>
                    @forelse ($histories as $key => $item)
                    <tr>
                        <td class="align-middle text-center">{{ $i++ }}</td>
                        <td>
                            <div class="d-flex px-2 py-1">
                            <div>
                                <img src="{{ asset('img/team-2.jpg') }}" class="avatar avatar-sm me-3" alt="user1">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ $item->user->name }}</h6>
                                <p class="text-xs text-secondary mb-0">{{ $item->user->email }}</p>
                            </div>
                            </div>
                        </td>
                        <td>
                            @foreach (json_decode($item->orders) as $orders)
                                <p class="text-xs font-weight-bold mb-0">{{ $orders->amount }} {{ $orders->name }}</p>
                            @endforeach
                        </td>
                        <td>
                            <span class="text-secondary text-xs font-weight-bold">Rp. {{ number_format(array_reduce( array_map(fn($el) => $el->amount * $el->price, json_decode($item->orders)), fn($a, $b) => $a + $b)) }}</span>
                        </td>
                        <td class="align-middle text-center">
                            <span class="text-secondary text-xs font-weight-bold">{{ date('d F Y', strtotime($item->created_at)) }}</span>
                        </td>
                        <td class="align-middle text-center">
                            <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                            Edit
                            </a>
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
          </div>
        </div>
      </div>
    </div>

</x-app-layout>