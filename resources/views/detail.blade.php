<x-app-layout>
    @if(session()->has('success'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="flash">
      {{ session()->get('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
    </div>
  @endif
    <div class="row">
      <div class="col-12 col-xl-4">
        <div class="card h-80 img-rounded">
          <div id="carouselExampleIndicators_{{ $product->id }}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-indicators">
              @foreach ($product->images as $item)
                <button type="button" data-bs-target="#carouselExampleIndicators_{{ $item->product_id }}" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
              @endforeach
            </div>
            <div class="carousel-inner">
              @forelse ($product->images as $key => $item)
                <div class="carousel-item @if($key === 0) active @endif">
                  <img src="{{ asset("product_image/$item->file") }}" class="d-block w-100" alt="{{ $item->file }}">
                </div>
              @empty
                <div class="carousel-item active">
                  <img src="{{ asset('img/product/sate.jpg') }}" class="d-block w-100" alt="Gambar">
                </div>
              @endforelse
            </div>
            @if (count($product->images) > 1)
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators_{{ $product->id }}" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators_{{ $product->id }}" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            @endif
          </div>
          @if (Auth::user()->role === 1)
            <div class="card-body">
              <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-trash"></i></button>
            </div>
          @endif
        </div>
      </div>
      <div class="col-12 col-xl-4">
        <div class="card h-100">
          <div class="card-header pb-0 p-3">
            <div class="row">
              <div class="col-md-8 d-flex align-items-center">
                <p class="m-0">Product Information</p>
              </div>
              <div class="col-md-4 text-end">
                <a href="javascript:;">
                  <i class="fas fa-info text-secondary text-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Product"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="card-body p-3">
            <h4>{{ $product->name }}</h4>
            <hr class="horizontal gray-light mb-3">
            <ul class="list-group">
              <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Full Name:</strong> &nbsp; {{ $product->name }}</li>
              <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Price:</strong> &nbsp; {{ $product->price }}</li>
              <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; alecthompson@mail.com</li>
              <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Location:</strong> &nbsp; USA</li>
            </ul>
            @if (Auth::user()->role === 1)
              <a class="btn btn-success mt-4" data-bs-toggle="modal" data-bs-target="#Editdata">Edit</a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">hapus Gambar</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
              <div class="modal-body">
              <div class="mb-3">
                <div class="row">
                  <div class="col-md-4 d-blok justify-content-center">
                      @foreach ($product->images as $item)
                        <div class="col-4 d-flex mb-3">
                          <img src="{{ asset("product_image/$item->file") }}" alt="" width="150px">
                          @if (Auth::user()->role === 1)
                            <form action="/images/{{ $item->id }}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button class="btn btn-danger align-items-center mx-2" type="submit" style="height: 40px">X</button>
                            </form>
                          @endif
                        </div>
                        @endforeach
                    </div>
                  </div>
              </div>
              </div>
              <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
          </div>
        </div>
      </div>

    <div class="modal fade" id="Editdata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @if (Auth::user()->role === 1)
            <form action="/product/{{ $product->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="int" class="form-control" id="price" name="price" value="{{ $product->price }}">
                </div>
                <div class="mb-3">
                  <label for="images">Images</label>
                  <div class="row">
                    <div class="col-md-4 justify-content-center">
                        @foreach ($product->images as $item)
                          <div class="col-4 d-flex mb-3">
                            <img src="{{ asset("product_image/$item->file") }}" alt="" width="150px">
                          </div>
                          @endforeach
                      </div>
                    </div>
                    <input type="file" name="images[]" class="form-control" id="images"multiple>
                </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit"  class="btn btn-primary">Update</button>
                </div>
            </form>
            @endif
          </div>
        </div>
      </div>

</x-app-layout>