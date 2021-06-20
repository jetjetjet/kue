<?php
  $rowIndex = $rowIndex ?? null;

  $menuId = isset($sub->spmenuid) && isset($rowIndex) ? $sub->spmenuid : null;
  $menuName = isset($sub->menuname) && isset($rowIndex) ? $sub->menuname : null;
  $menuCategory = isset($sub->menucategory) && isset($rowIndex) ? $sub->menucategory : null;
  $menuType = isset($sub->menutype) && isset($rowIndex) ? $sub->menutype : null;
  $menuAvailable = isset($sub->menuavaible) && isset($rowIndex) ? $sub->menuavaible : true;
  $menuPrice = isset($sub->menuprice) && isset($rowIndex) ? $sub->menuprice : 0;
  $menuPromo = isset($sub->menupromo) && isset($rowIndex) ? $sub->menupromo : 0;
?>

<tr class="subItem">
  <td style="padding-top:1px !important">
    <input type="hidden" name="sub[{{ $rowIndex }}][id]" value="{{ isset($rowIndex) && isset($sub->id) ? $sub->id : null }}" class=" text-right"/>
    <input type="hidden" name="sub[{{ $rowIndex }}][index]" value="{{ $rowIndex }}" />
    <input type="hidden" name="sub[{{ $rowIndex }}][menuname]" value="{{$menuName}}" />
    @if($canEdit)
      <select class="form-control input-sm" deliver-row name="sub[{{ $rowIndex }}][spmenuid]">
        @if(isset($menuId))
          <option value="{{$menuId}}"> {{ $menuName }} </option>
        @endif
      </select>
    @else
      {{ $menuName }} 
    @endif
  </td>
  <td style="padding-top:1px !important">
    <input type="hidden" name="sub[{{ $rowIndex }}][menutype]" value="{{$menuType}}" />
    <p id="sub[{{ $rowIndex }}][menuType]">{{$menuType}}</p>
  </td>
  <td style="padding-top:1px !important">
    <input type="hidden" name="sub[{{ $rowIndex }}][menucategory]" value="{{$menuCategory}}" />
    <p id="sub[{{ $rowIndex }}][menuCategory]">{{$menuCategory}}</p>
  </td>
  <td style="padding-top:1px !important">
    <input type="hidden" name="sub[{{ $rowIndex }}][menuprice]" value="{{$menuPrice}}" />
    <p id="sub[{{ $rowIndex }}][menuPriceText]">{{number_format($menuPrice)}}</p>
  </td>
  <td style="padding-top:1px !important">
    <input type="hidden" name="sub[{{ $rowIndex }}][menupromo]" value="{{$menuPromo}}" />
    <p id="sub[{{ $rowIndex }}][menuPromo]">{{number_format($menuPromo)}}</p>
  </td>
  <td style="padding-top:1px !important">
    <ul class="table-controls">
      <input type="hidden" name="sub[{{ $rowIndex }}][menuavaible]" value="{{$menuAvailable}}" />
      <li id="sub[{{ $rowIndex }}][subAvail]" class="{{$menuAvailable ? '' : 'd-none'}}" title="Menu Tersedia"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle text-primary"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg></li>
      <li id="sub[{{ $rowIndex }}][subNotAvail]" class="{{!$menuAvailable ? '' : 'd-none'}}" title="Menu Tidak Tersedia"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle text-danger"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></li>
    </ul>
  </td>
  <td style="padding-top:1px !important">
    @if($canEdit)
      <button type="button" id="sub[{{ $rowIndex }}][deleteRow]" title="Hapus Menu" style="border:none; background:transparent" remove-row>
        <span class="badge badge-danger">Hapus<i class="far fa-times-circle"></i></span>
      </button>
    @endif
  </td>
</tr>