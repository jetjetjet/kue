<?php
  $rowIndex = $rowIndex ?? null;

  $productText = $sub->odproducttext ?? null;
  $showcaseText = $sub->showcasecode ?? null;
  $productPrice = $sub->odprice ?? null;
  $productPriceRaw = $sub->odpriceraw ?? null;
  $productid = $sub->odproductid ?? null;
  $productQty = $sub->odqty ?? null;
  $productRemark = $sub->odremark ?? null;
  $productTotalprice = $sub->odtotalprice ?? null;
  $odshowcaseid = $sub->odshowcaseid ?? null;
  $odshowcasecode = $sub->odshowcasecode ?? null;
  $odtype = $sub->odtype ?? null;
  $showcaseCode = $sub->showcaseCode ?? null;
  $orderPaid = $data->orderpaid;
// dd($sub);
?>

<tr class="subitem">
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odpromoid]" value="{{ isset($rowIndex) && isset($sub->odpromoid) ? $sub->odpromoid : null }}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odproductid]" value="{{$productid}}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odproducttext]" value="{{$productText}}" class=" text-right"/>
    <p id="dtl[{{ $rowIndex }}][odproducttext]"><span class="badge outline-badge-info {{ isset($rowIndex) && isset($sub->odpromoid) ? '' : 'd-none' }}"> Promo </span>&nbsp;{{$productText}}</p>
    <p id="dtl[{{ $rowIndex }}][odshowcase]">{{ !empty($sub->showcasecode) ? 'Kd. Produksi:' . $showcaseText : '' }}</p>
  </td>
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odshowcaseid]" value="{{$odshowcaseid}}" />
    <input type="hidden" name="dtl[{{ $rowIndex }}][odshowcasecode]" value="{{$odshowcasecode}}" />
    @if(($data->orderstatus != 'DRAFT' && !isset($rowIndex)) ||  Perm::can(['order_deleteDetail']) )
      <select id="productType" class="" name="dtl[{{ $rowIndex }}][odtype]" deliver-row>
        <option value="PO">{{ trans('fields.preOrder') }}</option>
        <option value="READYSTOCK">{{ trans('fields.readyStock') }}</option>
      </select>
    @else
      <p id="dtl[{{ $rowIndex }}][odshowcase]">{{ $odtype }}</p>
    @endif
  </td>
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odprice]" value="{{$productPrice}}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odpriceraw]" value="{{$productPriceRaw}}" class=" text-right"/>
    <p width="40%" id="dtl[{{ $rowIndex }}][odprice]">{{ number_format($productPrice,0) }}</p>
    <input type="hidden" name="dtl[{{ $rowIndex }}][id]" value="{{ isset($rowIndex) && isset($sub->id) ? $sub->id : null }}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][index]" value="{{ $rowIndex }}" class=" text-right"/>
  </td>
  <td class="text-center">
    @if(($data->orderstatus != 'DRAFT' && !isset($rowIndex)) ||  Perm::can(['order_deleteDetail']) )
      <span class="input-number-decrement" counter-down>–</span>
        <input type="number" class="input-number subQty" min="1" name="dtl[{{ $rowIndex }}][odqty]" value="{{$productQty}}" sub-input >
      <span class="input-number-increment" counter-up>+</span> 
    @else
      <input type="hidden" name="dtl[{{ $rowIndex }}][odqty]" value="{{$productQty}}">
      <p class="text-center">{{ $productQty }}</p>
    @endif
  </td>
  <td>
    <div id="Totalp"><input type="hidden" name="dtl[{{ $rowIndex }}][odtotalprice]" value="{{$productTotalprice}}" />
      <p id="dtl[{{ $rowIndex }}][odtotalprice]" >{{ number_format($productTotalprice,0) }}</p>
    </div>
  </td>
  <td>
    <input type="text" value="{{$productRemark}}" name="dtl[{{ $rowIndex }}][odremark]" style="width: 60px;">
  </td>
  <td>
  @if(($data->orderstatus != 'DRAFT' && !isset($rowIndex)) ||  Perm::can(['order_deleteDetail']) )
    <button type="button" id="dtl[{{ $rowIndex }}][deleteRow]" title="Hapus Pesanan" style="border:none; background:transparent" remove-row>
      <span class="badge badge-danger">Hapus</span>
    </button>
  @endif
  </td>
</tr>