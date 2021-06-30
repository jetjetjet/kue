<?php
  $rowIndex = $rowIndex ?? null;

  $productId = isset($sub->spproductid) && isset($rowIndex) ? $sub->spproductid : null;
  $productName = isset($sub->productname) && isset($rowIndex) ? $sub->productname : null;
  $productCategory = isset($sub->productcategory) && isset($rowIndex) ? $sub->productcategory : null;
  $productCode = isset($sub->productcode) && isset($rowIndex) ? $sub->productcode : 0;
  $productPrice = isset($sub->productprice) && isset($rowIndex) ? $sub->productprice : 0;
  $productPromo = isset($sub->productpromo) && isset($rowIndex) ? $sub->productpromo : 0;

  // dd($productId);
?>

<tr class="subItem">
  <td style="padding-top:1px !important">
    <input type="hidden" name="sub[{{ $rowIndex }}][id]" value="{{ isset($rowIndex) && isset($sub->id) ? $sub->id : null }}" class=" text-right"/>
    <input type="hidden" name="sub[{{ $rowIndex }}][index]" value="{{ $rowIndex }}" />
    <input type="hidden" name="sub[{{ $rowIndex }}][productname]" value="{{$productName}}" />
    @if($canEdit)
      <select class="form-control input-sm" deliver-row name="sub[{{ $rowIndex }}][spproductid]">
        @if(isset($productId))
          <option value="{{$productId}}"> {{ $productName }} </option>
        @endif
      </select>
    @else
      {{ $productName }} 
    @endif
  </td>
  <td style="padding-top:1px !important">
    <input type="hidden" name="sub[{{ $rowIndex }}][productcode]" value="{{$productCode}}" />
    <p id="sub[{{ $rowIndex }}][productCode]">{{$productCode}}</p>
  </td>
  <td style="padding-top:1px !important">
    <input type="hidden" name="sub[{{ $rowIndex }}][productcategory]" value="{{$productCategory}}" />
    <p id="sub[{{ $rowIndex }}][productCategory]">{{$productCategory}}</p>
  </td>
  <td style="padding-top:1px !important">
    <input type="hidden" name="sub[{{ $rowIndex }}][productprice]" value="{{$productPrice}}" />
    <p id="sub[{{ $rowIndex }}][productPriceText]">{{number_format($productPrice)}}</p>
  </td>
  <td style="padding-top:1px !important">
    <input type="hidden" name="sub[{{ $rowIndex }}][productpromo]" value="{{$productPromo}}" />
    <p id="sub[{{ $rowIndex }}][productPromo]">{{number_format($productPromo)}}</p>
  </td>
  <td style="padding-top:1px !important">
    @if($canEdit)
      <button type="button" id="sub[{{ $rowIndex }}][deleteRow]" title="Hapus Product" style="border:none; background:transparent" remove-row>
        <span class="badge badge-danger">Hapus<i class="far fa-times-circle"></i></span>
      </button>
    @endif
  </td>
</tr>