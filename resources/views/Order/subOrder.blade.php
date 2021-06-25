<?php
  $rowIndex = $rowIndex ?? null;

  $productText = $sub->odproducttext ?? null;
  $productPrice = $sub->odprice ?? null;
  $productPriceRaw = $sub->odpriceraw ?? null;
  $productid = $sub->odproductid ?? null;
  $productQty = $sub->odqty ?? null;
  $productRemark = $sub->odremark ?? null;
  $productDeliver = $sub->oddelivertext ?? null;
  $productDelivered = $sub->oddelivered ?? null;
  $productTotalprice = $sub->odtotalprice ?? null;
  $odshowcaseid = $sub->odshowcaseid ?? null;
  $odshowcasecode = $sub->odshowcasecode ?? null;
  $odtype = $sub->odtype ?? null;
  $showcaseCode = $sub->showcaseCode ?? null;
  $orderPaid = $data->orderpaid;
  $canUpd = Perm::can(['order_save']) && ($sub->oddelivertext ?? false);
// dd($sub);
?>

<tr class="subitem">
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odpromoid]" value="{{ isset($rowIndex) && isset($sub->odpromoid) ? $sub->odpromoid : null }}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odproductid]" value="{{$productid}}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odproducttext]" value="{{$productText}}" class=" text-right"/>
    <p id="dtl[{{ $rowIndex }}][odproducttext]"><span class="badge outline-badge-info {{ isset($rowIndex) && isset($sub->odpromoid) ? '' : 'd-none' }}"> Promo </span>&nbsp;{{$productText}}</p>
    <p id="dtl[{{ $rowIndex }}][odshowcase]"></p>
  </td>
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odshowcaseid]" value="{{$odshowcaseid}}" />
    <input type="hidden" name="dtl[{{ $rowIndex }}][odshowcasecode]" value="{{$odshowcasecode}}" />
    <select id="productType" class="" name="dtl[{{ $rowIndex }}][odtype]" deliver-row>
      <option value="PO">{{ trans('fields.preOrder') }}</option>
      <option value="READYSTOCK">{{ trans('fields.readyStock') }}</option>
    </select>
  </td>
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odprice]" value="{{$productPrice}}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odpriceraw]" value="{{$productPriceRaw}}" class=" text-right"/>
    <p width="40%" id="dtl[{{ $rowIndex }}][odprice]">{{ number_format($productPrice,0) }}</p>
    <input type="hidden" name="dtl[{{ $rowIndex }}][id]" value="{{ isset($rowIndex) && isset($sub->id) ? $sub->id : null }}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][index]" value="{{ $rowIndex }}" class=" text-right"/>
  </td>
  <td class="text-center">
    @if((isset($rowIndex) && $productDelivered) || !empty($data->ordervoidedat))
      <input type="hidden" name="dtl[{{ $rowIndex }}][odqty]" value="{{$productQty}}">
      <p class="text-center">{{ $productQty }}</p>
    @else
      <span class="input-number-decrement" counter-down>–</span>
        <input type="number" class="input-number subQty" min="1" name="dtl[{{ $rowIndex }}][odqty]" value="{{$productQty}}" sub-input >
      <span class="input-number-increment" counter-up>+</span> 
    @endif
  </td>
  <td>
    <div id="Totalp"><input type="hidden" name="dtl[{{ $rowIndex }}][odtotalprice]" value="{{$productTotalprice}}" />
      <p id="dtl[{{ $rowIndex }}][odtotalprice]" >{{ number_format($productTotalprice,0) }}</p>
    </div>
  </td>
  <td>
    @if((isset($rowIndex) && $productDelivered) || !empty($data->ordervoidedat))
      <p class="text-center">{{ $productRemark }}</p>
      <input type="hidden" value="{{$productRemark}}" name="dtl[{{ $rowIndex }}][odremark]" style="width: 60px;">
    @else
      <input type="text" value="{{$productRemark}}" name="dtl[{{ $rowIndex }}][odremark]" style="width: 60px;">
    @endif
  </td>
  <td>
  @if((isset($rowIndex) && $orderPaid))
    <button type="button" title="Pesanan Selesai Diantar" id="dtl[{{ $rowIndex }}][delivRow]" style="border:none; background:transparent" deliver-row>
      <span class="badge badge-info">Ubah<i class="far fa-check-square"></i></span>
    </button>
  @else
    <button type="button" id="dtl[{{ $rowIndex }}][deleteRow]" title="Hapus Pesanan" style="border:none; background:transparent" remove-row>
      <span class="badge badge-danger">Hapus<i class="far fa-times-circle"></i></span>
    </button>
  @endif
  </td>
</tr>