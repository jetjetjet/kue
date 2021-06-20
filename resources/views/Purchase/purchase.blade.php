@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>{{ trans('fields.purchase') }}</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">{{ trans('fields.purchase') }}</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? trans('fields.addNew') : trans('fields.edit')}}</a></li>
  </ol>
@endsection

@section('content-form')
  <div class="d-flex justify-content-between">
    <div class="col-8">
      <div class="skills layout-spacing">
        <div class="widget-content widget-content-area">
          <div class="form-row">
            <form class="col-12">
              <div class="form-group row col-7">
                <label for="hEmail" class="col-xl-3 col-sm-3 col-sm-3 col-form-label">Cari {{ trans('fields.product') }}</label>
                <div class="col-xl-9 col-lg-9 col-sm-9">
                  <select class="" id="productsearch" name="showcaseproductid">
                    <option value="">Hapus</option>
                    {{-- @if($data->showcaseproductid)
                      <option value="{{$data->showcaseproductid}}" selected="selected">{{$data->productcode}} - {{$data->productname}}</option>
                    @endif --}}
                  </select>
                </div>
              </div>
              <div class="col-5">
              
              </div>
            </form>
            <table id="detailOrder" class="table table-hover dtl-order">
              <thead>
                <tr>
                  <th width="40%">Menu</th>
                  <th>Harga</th>
                  <th width="20%" class="text-center">qty</th>
                  <th>Total</th>
                  <th>Cttn</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data->items as $item)
                  @include('Purchase.subPurchase', Array('rowIndex' => $loop->index))
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-4">
      <div class="skills layout-spacing">
        <div class="widget-content widget-content-area">
          
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js-form')
  <script>
    $(document).ready(function (){
      console.log(JSON.parse('{!! $products !!}'))

      $('#productsearch').select2({
        data: JSON.parse('{!! $products !!}')
      });
    });
  </script>
@endsection