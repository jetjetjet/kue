<?php
  $rowIndex = $rowIndex ?? null;

  $menuText = $sub->odmenutext ?? null;
  $menuPrice = $sub->odprice ?? null;
  $menuPriceRaw = $sub->odpriceraw ?? null;
  $menuid = $sub->odmenuid ?? null;
  $menuQty = $sub->odqty ?? null;
  $menuRemark = $sub->odremark ?? null;
  $menuDeliver = $sub->oddelivertext ?? null;
  $menuDelivered = $sub->oddelivered ?? null;
  $menuTotalprice = $sub->odtotalprice ?? null;
  $canUpd = Perm::can(['order_save']) && ($sub->oddelivertext ?? false);
// dd($sub);
?>

<tr class="subitem">
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odpromoid]" value="{{ isset($rowIndex) && isset($sub->odpromoid) ? $sub->odpromoid : null }}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odmenuid]" value="{{$menuid}}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odmenutext]" value="{{$menuText}}" class=" text-right"/>
    <p id="dtl[{{ $rowIndex }}][odmenutext]"><span class="badge outline-badge-info {{ isset($rowIndex) && isset($sub->odpromoid) ? '' : 'd-none' }}"> Promo </span>&nbsp;{{$menuText}}</p>
  </td>
  <td>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odprice]" value="{{$menuPrice}}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][odpriceraw]" value="{{$menuPriceRaw}}" class=" text-right"/>
    <p width="40%" id="dtl[{{ $rowIndex }}][odprice]">{{ number_format($menuPrice,0) }}</p>
    <input type="hidden" name="dtl[{{ $rowIndex }}][id]" value="{{ isset($rowIndex) && isset($sub->id) ? $sub->id : null }}" class=" text-right"/>
    <input type="hidden" name="dtl[{{ $rowIndex }}][index]" value="{{ $rowIndex }}" class=" text-right"/>
  </td>
  <td class="text-center">
    @if((isset($rowIndex) && $menuDelivered) || !empty($data->ordervoidedat))
      <input type="hidden" name="dtl[{{ $rowIndex }}][odqty]" value="{{$menuQty}}">
      <p class="text-center">{{$menuQty}}</p>
    @else
      <span class="input-number-decrement" counter-down>–</span>
        <input type="number" class="input-number subQty" min="1" name="dtl[{{ $rowIndex }}][odqty]" value="{{$menuQty}}" sub-input >
      <span class="input-number-increment" counter-up>+</span> 
    @endif
  </td>
  <td>
    <div id="Totalp"><input type="hidden" name="dtl[{{ $rowIndex }}][odtotalprice]" value="{{$menuTotalprice}}" />
      <p id="dtl[{{ $rowIndex }}][odtotalprice]" >{{ number_format($menuTotalprice,0) }}</p>
    </div>
  </td>
  <td>
    @if((isset($rowIndex) && $menuDelivered) || !empty($data->ordervoidedat))
      <p class="text-center">{{ $menuRemark }}</p>
      <input type="hidden" value="{{$menuRemark}}" name="dtl[{{ $rowIndex }}][odremark]" style="width: 60px;">
    @else
      <input type="text" value="{{$menuRemark}}" name="dtl[{{ $rowIndex }}][odremark]" style="width: 60px;">
    @endif
  </td>
    <td>
    @if((isset($rowIndex) && $menuDelivered))
      <p class="text-center"><i class="far fa-check-square"></i></p>
    @elseif(!empty($data->ordervoidedat))
      <p class="text-center"><i class="far fa-closed"></i></p>
    @else
      <button type="button" id="dtl[{{ $rowIndex }}][deleteRow]" title="Hapus Pesanan" style="border:none; background:transparent" remove-row>
        <span class="badge badge-danger">H <i class="far fa-times-circle"></i></span>
      </button>
      @if(isset($sub->id) && isset($rowIndex) && !$menuDelivered && Perm::can(['order_pelayan']))
        <button type="button" title="Pesanan Selesai Diantar" id="dtl[{{ $rowIndex }}][delivRow]" style="border:none; background:transparent" deliver-row>
          <span class="badge badge-info">S <i class="far fa-check-square"></i></span>
        </button>
      @endif
    @endif
  </td>
</tr>