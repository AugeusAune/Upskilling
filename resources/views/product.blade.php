<x-app-layout>

    @if(session()->has('success'))
      <div class="alert alert-warning alert-dismissible fade show" role="alert" id="flash">
        {{ session()->get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
      </div>
    @endif

    <div class="container bg-white" style="min-height: 80vh">
      <div class="row">
        <div class="col-lg-9 col-sm-6 mb-xl-0">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex justify-content-center align-items-center">
              <div class="search">
                <form action="" class="d-flex">
                    <input type="hidden" name="sort" value="{{ request()->sort }}">
                    <div class="input-group">
                      <input type="text" class="form-control px-2" name="search" value="{{ request()->search }}" placeholder="Search here...">
                      <button class="input-group-text text-body" type="submit"><i class="fas fa-search" aria-hidden="true"></i></button>
                  </div>
                </form>
              </div>
              <div class="filter mx-4">
                <form action="" method="GET" id="formSort">
                  <input type="hidden" name="search" value="{{ request()->search }}">
                  <select class="form-select pe-5" aria-label="Default select example" name="sort" onchange="document.getElementById('formSort').submit()">
                    <option {{ request()->sort == 1 ? 'selected' : '' }} value="1">Sort By</option>
                    <option {{ request()->sort == 2 ? 'selected' : '' }} value="2">A-Z</option>
                    <option {{ request()->sort == 3 ? 'selected' : '' }} value="3">Z-A</option>
                    <option {{ request()->sort == 4 ? 'selected' : '' }} value="4">Termurah</option>
                    <option {{ request()->sort == 5 ? 'selected' : '' }} value="5">Termahal</option>
                  </select>
                </form>
              </div>
            </div>
            <div class="add">
              @if (Auth::user()->role === 1)
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  Add Product
                </button>
              @endif
            </div>
          </div>
          <div class="row">
            @foreach ($products as $product)
              <div class="col-lg-4 col-md-3 col-sm-6 col">
                  <div class="card mb-4" style="width: 18rem;">
                      {{-- Carousel --}}
                      <a href="/product/{{ $product->id }}">
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
                      </a>          
                      {{-- Cardbody --}}
                      <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="left">
                              <h5 class="card-text">{{ $product->name }}</h5>
                              <p class="card-text">Rp.{{ $product->price }}</p>
                            </div>
                            <div class="right">
                              <div class="cart" onclick="cart({{$product}})" id="buttonToggleProduct-{{ $product->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                  <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                                </svg>
                              </div>
                            </div>
                        </div>
                      </div>
                      {{-- Delete Footer --}}
                      @if (Auth::user()->role === 1)
                        <div class="card-footer py-0 my-0 d-flex" >
                          <form action="/product/{{ $product->id }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn p-1"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"     fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                              <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>Hapus Product</button>
                          </form>
                        </div>
                      @endif
                  </div>
              </div>
            @endforeach
               {{-- Pagination --}}
            <div class="d-flex justify-content-center">
              {{ $products->links() }}  
            </div>
          </div>
        </div>
        <div class="col border-start bg-white" id="cartSection" style="min-height: 80vh;">
          <h3>Cart</h3>
          <hr>
          <div id="productsOnCart" class="overflow-auto" style="max-height: 52vh; min-height:52vh"></div>
          <div>
            <hr>
            <h3 id="totalAmount"></h3>
            <p>* Tax not Included</p>
            <div class="d-flex w-100">
              <button class="btn btn-danger w-100 mx-2" onclick="removeCart()">Hapus Cart</button>
              <button class="btn btn-success w-100 mx-2" onclick="checkOut()">Beli</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Modals --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
          </div>
          <form action="{{ route('storeProduct') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old("name") ? old('name') : '' }}">
                @error('name')
                    <p class="text-danger text-small mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="int" class="form-control" id="price" name="price" value="{{ old("price") ? old('price') : '' }}">
                @error('price')
                    <p class="text-danger text-small mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div class="mb-3">
                <label for="images" class="form-label">Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple>
                @error('images.*')
                    <p class="text-danger text-small mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit"  class="btn btn-primary">Add</button>
            </div>
        </form>
        </div>
      </div>
    </div>

    {{-- script --}}
    <script>

      const removeCart = () => {
        localStorage.removeItem('cart')
        location.reload()
      }

      const toggleStateProduct = (productId, toggleTo) => {
        let cart = JSON.parse(localStorage.getItem('cart')) ? JSON.parse(localStorage.getItem('cart')) : []
        if(toggleTo === 'add') {
          document.getElementById(`buttonToggleProduct-${productId}`).innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16"><path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>'
          const productOnCart = document.getElementById(`productIdOnCart-${productId}`)
          productOnCart.parentNode.removeChild(productOnCart)
        } else if(toggleTo === 'remove') {
          const productData = cart.filter(el => el.id == productId)[0]
          document.getElementById(`buttonToggleProduct-${productId}`).innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/></svg>'
          const productOnCart = document.getElementById('productsOnCart')
          let child = document.createElement("div")
          child.setAttribute('id', `productIdOnCart-${productId}`)
          child.innerHTML = `
            <div class="card mb-2" style="max-width: 540px;">
              <div class="row g-0 align-items-center">
                <div class="col-md-3">
                  ${productData && productData.images.length > 0 ?  '<img src="{{ asset("product_image/$product->image[0]") }}" class="d-block w-100" alt="">' : '<img src="{{ asset("img/product/sate.jpg") }}" class="d-block w-100" alt="">'}
                </div>
                <div class="col-md-9">
                  <div class="card-body">
                    <div class="d-flex justify-content-between">
                      <p style="font-size:13px" class="card-title">${productData && productData.name ? productData.name : 'No Judul'}</p>
                      <p class="card-text"><small id="productPrice-${productId}" style="font-size:12px">Rp.${productData && productData.price && productData.amount ? (productData.price * productData.amount).toLocaleString('id-ID') : 'No Judul'}</small></p>
                    </div>
                    <div class="input-group mb-3">
                      <input type="number" onchange="totalAmount(${productId})" id="productAmount-${productId}" class="form-control px-2 text-center" placeholder="Amount" value="${productData && productData.amount ? productData.amount : 'No Amount'}">
                      <span class="input-group-text"><i class="fas fa-trash"></i></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>`
          productOnCart.append(child); 
        }
        document.getElementById('totalAmount').innerHTML = `Rp. ${cart.map(el => el.price * el.amount).reduce((a,b) => a+b, 0).toLocaleString('id-ID')}`
      }

      const toggleInState = () => {
        let cart = JSON.parse(localStorage.getItem('cart')) ? JSON.parse(localStorage.getItem('cart')) : []
        cart.map(product => {
          toggleStateProduct(product.id, 'remove')
        })  
      }

      const totalAmount = (productId) => {
        const cart = JSON.parse(localStorage.getItem('cart')) ? JSON.parse(localStorage.getItem('cart')) : []
        const amount = document.getElementById(`productAmount-${productId}`).value
        let productData = cart.filter(el => {
          if(el.id === productId) {
            return el.amount = amount
          } else {
            return el
          }
        })
        const productDetailData = productData.filter(el => el.id === productId)[0]
        localStorage.setItem('cart', JSON.stringify(productData))
        document.getElementById(`productPrice-${productId}`).innerHTML = `Rp.${productDetailData.price && productDetailData ?(productDetailData.price * productDetailData.amount).toLocaleString('id-ID') : ''} `
        document.getElementById('totalAmount').innerHTML = `Rp. ${cart.map(el => el.price * el.amount).reduce((a,b) => a+b, 0).toLocaleString('id-ID')}`  
      }

      const cart = (product) => {
        console.log(product);
        let cart = JSON.parse(localStorage.getItem('cart')) ? JSON.parse(localStorage.getItem('cart')) : []
        if(cart.filter(el => el.id === product.id).length > 0) {
          localStorage.setItem('cart', JSON.stringify([...cart.filter(el => el.id !== product.id)]))
          toggleStateProduct(product.id, 'add')
        } else {
          localStorage.setItem('cart', JSON.stringify([...cart, {...product, amount: 1}]))
          toggleStateProduct(product.id, 'remove')
        }
      }

      const checkOut = () => {
        const cart = JSON.parse(localStorage.getItem('cart')) ? JSON.parse(localStorage.getItem('cart')) : []
        const dataToBackend = cart.map(el => {
          return {
            id: el.id,
            amount: el.amount
          }
        })
        fetch('{{ request()->fullUrl() }}/history', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            data: dataToBackend,
            _token: "{{ csrf_token() }}"
            })
        })
        .then(res => res.json())
        .then(res => {
          alert('Success Add to Cart')
          removeCart()
        })
        .catch(err => console.log(err))
      }

      toggleInState()
    </script>

</x-app-layout>