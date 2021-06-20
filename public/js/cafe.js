$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function getIPWS()
{
  return $('meta[name="ipws"]').attr('content')
}

function toggleFullScreen() {
  if ((document.fullScreenElement && document.fullScreenElement !== null) ||
      (!document.mozFullScreen && !document.webkitIsFullScreen)) {
      if (document.documentElement.requestFullScreen) {
          document.documentElement.requestFullScreen();
      } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
      } else if (document.documentElement.webkitRequestFullScreen) {
          document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
      }
  } else {
      if (document.cancelFullScreen) {
          document.cancelFullScreen();
      } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
      } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
      }
  }
}

//ping printer
Mousetrap.bind('p', function(){
  const url = $('#ping').val()
  $('.spinHotkeys').removeClass('d-none')
  $.ajax({
    url: url,
    type: "post",
    success: function(result){
      //console.log(result);
      var msg = result.messages[0];
      if(result.status == 'success'){
        swal({
          title: 'Terhubung',
          text: 'Printer Sudah Terhubung',
          type: 'success',
          padding: '2em'
        })
      }else{
        swal({
          title: 'Printer Tidak Terhubung',
          text: 'Cek kertas printer, Cek kabel, Jika tidak kunjung bisa, Hubungi Administrator',
          type: 'error',
          padding: '2em'
        })
      }
      $('.spinHotkeys').addClass('d-none')
    },
    error:function(error){
      $('.spinHotkeys').addClass('d-none')
    }
    })
})
//endping

// BukaLaci
Mousetrap.bind('esc', function(){
  const swalWithBootstrapButtons = swal.mixin({
    input: 'password',
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
  })
  const toast = swal.mixin({
    toast: true,
    position: 'center',
    showConfirmButton: false,
    timer: 3000,
    padding: '2em'
  });
  swalWithBootstrapButtons({
    title: 'Buka Laci',
    text: 'Masukkan Password',
    type: 'question',
    showCancelButton: true,
    confirmButtonText: 'Buka',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    padding: '2em',
    preConfirm: function(result) {
      if (result) { 
      } else {
        Swal.showValidationError('Password Harus Dimasukkan')        
      }
    }
    }).then(function(result) {
      //console.log(result)
      if (result.value) {
        $('.spinHotkeys').removeClass('d-none')
        const url = $('#bukalaci').val()
        $.post( url,{'pass':result.value}, function (data){         
          if (data.status == 'success'){
            toast({
              type: 'success',
              title: 'Laci Dibuka',
              padding: '2em',
              })
          } else {
            toast({
              type: 'error',
              title: data.messages[0],
              padding: '2em',
              })
          }
          $('.spinHotkeys').addClass('d-none')
        });
      }
    });
  });

  Mousetrap.bind('*', function(){
    const swalWithBootstrapButtons = swal.mixin({
      input: 'password',
      confirmButtonClass: 'btn btn-success btn-rounded',
      cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
      buttonsStyling: false,
    })
    const toast = swal.mixin({
      toast: true,
      position: 'center',
      showConfirmButton: false,
      timer: 3000,
      padding: '2em'
    });
    swalWithBootstrapButtons({
      title: 'Buka Laci',
      text: 'Masukkan Password',
      type: 'question',
      showCancelButton: true,
      confirmButtonText: 'Buka',
      cancelButtonText: 'Batal',
      reverseButtons: true,
      padding: '2em',
      preConfirm: function(result) {
        if (result) { 
        } else {
          Swal.showValidationError('Password Harus Dimasukkan')        
        }
      }
      }).then(function(result) {
        //console.log(result)
        if (result.value) {
          const url = $('#bukalaci').val()
          $.post( url,{'pass':result.value}, function (data){         
            if (data.status == 'success'){
              toast({
                type: 'success',
                title: 'Laci Dibuka',
                padding: '2em',
                })
            } else {
              toast({
                type: 'error',
                title: data.messages[0],
                padding: '2em',
                })
            }
          });
        }
      });
    });

// end bukalaci

// to board
Mousetrap.bind('.', function(){
  const url = $('#board').val()
  window.location.href = url;
})

//end board
let formatter = new Intl.NumberFormat();

function cloneModal($idModal){
  $('#uiModalInstance').remove();
  $('.modal-backdrop').remove();

  var $modal = $idModal.clone().appendTo('body');
  $modal.attr('id', 'uiModalInstance');

  return $modal;
}

function sweetAlert(header, message, status){
  return swalWithBootstrapButtons(
    header,
    message,
    status
  );
}

function gridDeleteInput(url, title, message, grid){
  const swalWithBootstrapButtons = swal.mixin({
    input: 'textarea',
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
  })
  
  swalWithBootstrapButtons({
    title: title,
    text: message,
    type: 'question',
    showCancelButton: true,
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    padding: '2em'
  }).then(function(result) {
    if (result.value) {
      $.post(url,{'shiftdeleteremark':result.value}, function (data){
        if (data.status == 'success'){
          sweetAlert('Data Dihapus', data.messages[0], 'success')
        } else {
          sweetAlert('Kesalahan!', data.messages[0], 'error')
        }
        grid.ajax.reload();
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {
      sweetAlert('Batal','Shift batal hapus','error')
    } else {
      sweetAlert('Kesalahan!','Alasan menghapus harus di isi','error')
    }
  });
}

function gridDeleteInput2(url, title, message){
  const swalWithBootstrapButtons = swal.mixin({
    input: 'textarea',
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
  })
  
  swalWithBootstrapButtons({
    title: title,
    text: message,
    type: 'question',
    showCancelButton: true,
    confirmButtonText: 'Proses',
    cancelButtonText: 'Tutup',
    reverseButtons: true,
    padding: '2em'
  }).then(function(result) {
    if (result.value) {
      $.post(url,{'ordervoidreason':result.value}, function (data){
        if (data.status == 'success'){
          sweetAlert('Pesanan dibatalkan', data.messages[0], 'success')
          location.reload();
        } else {
          sweetAlert('Kesalahan!', data.messages[0], 'error')
        }
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {
      // sweetAlert('Batal','Pesanan tidak dibatalkan','error')
    } else {
      sweetAlert('Kesalahan!','Catatan pembatalan harus di isi','error')
    }
  });
}

function gridDeleteInput3(url, title, message, callbackfn){
  const swalWithBootstrapButtons = swal.mixin({
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
  })
  
  swalWithBootstrapButtons({
    title: title,
    text: message,
    type: 'question',
    showCancelButton: true,
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Tutup',
    reverseButtons: true,
    padding: '2em'
  }).then(function(result) {
    if (result.value) {
      $.post(url,{'ordervoidreason':result.value}, function (data){
        if (data.status == 'success'){
          sweetAlert('Pesanan', data.messages[0], 'success')
          if(callbackfn)
            callbackfn(null)
        } else {
          sweetAlert('Kesalahan!', data.messages[0], 'error')
        }
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {
      // sweetAlert('Batal','Pesanan tidak dibatalkan','error')
    }
  });
}

function gridDeleteRow(url, title, message, grid){
  const swalWithBootstrapButtons = swal.mixin({
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
  })

  swalWithBootstrapButtons({
    title: title,
    text: message,
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    padding: '2em'
  }).then(function(result) {
    if (result.value) {
      $.post(url, function (data){
        if (data.status == 'success'){
          sweetAlert('Data Dihapus', data.messages[0], 'success')
        } else {
          sweetAlert('Kesalahan!', data.messages[0], 'error')
        }
        grid.ajax.reload();
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {
      sweetAlert('Batal','Data meja batal hapus','error')
    }
  });
}

function gridDeleteSub(url, title, message, callBackfn){
  const swalWithBootstrapButtons = swal.mixin({
    confirmButtonClass: 'btn btn-success btn-rounded',
    cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
    buttonsStyling: false,
  })

  swalWithBootstrapButtons({
    title: title,
    text: message,
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Hapus',
    cancelButtonText: 'Batal',
    reverseButtons: true,
    padding: '2em'
  }).then(function(result) {
    if (result.value) {
      $.post(url, function (data){
        if(callBackfn)
          callBackfn(data)
      });
    } else if (
      result.dismiss === swal.DismissReason.cancel
    ) {
      sweetAlert('Batal','Data batal dihapus','error')
    }
  });
}

function showPopupOrder(paramBody, actFn){
  // Enables modal on current element.
  $(this).attr('data-toggle', 'modal');
  $(this).attr('data-target', '#uiModalInstance');

  let $modal = cloneModal($('#menuModal'));
  // $('#uiModalInstance').modal({
  //   // backdrop: 'static',
  //   keyboard: true
  // });
  $modal.on('show.bs.modal', function (){
      // Draws text.
      let pricePromoText = '';
      if(paramBody['promo']){
        pricePromoText = '&nbsp;<span class="badge outline-badge-info"> Harga Normal ' + paramBody['priceRaw'] +'</span>';
        $modal.find('#rowPromo').removeClass('d-none')
        $modal.find('#menuPopupPromo').html(paramBody['promo'] + '<p><span class="badge outline-badge-info"> Promo '
          + paramBody['promoText'] + ' s/d '+ paramBody['promoEnd'] +'</span></p>');
      }

      $modal.find('.modal-title').html('Tambah');
      $modal.find('#menuPopupText').html(paramBody['text']);
      $modal.find('#menuPopupPrice').html(paramBody['price'] + pricePromoText);

      if(paramBody['promo']){
        $modal.find('#rowPromo').removeClass('d-none')
        $modal.find('#menuPopupPromo').html(paramBody['promo'] + '<p><span class="badge outline-badge-info"> Promo '
          + paramBody['promoText'] + ' s/d '+ paramBody['promoEnd'] +'</span></p>');
      }
      
      let inputQty = $modal.find('#menuPopupQty');
      //console.log(inputQty)
      inputNumber(inputQty);
      $modal.modal({
          backdrop: 'static',
          keyboard: false
        });
      $('.modal-add-row')
      .click(function (){
        if (actFn){
          actFn();
        }
        $modal.modal('hide');
      });
  }).modal('show');
}

$.fn.registerAddRow = function ($rowTemplateContainer, $addRow, rowAddedFn, validationFn){
  $(this).each(function (){
    var $targetContainer = $(this),
      $tbody = $targetContainer.find('> tbody'),
      currentRowIndex = ($tbody.length ? $tbody : $targetContainer).children().length - 1;
    $targetContainer.attr('wbl-last-row-index', currentRowIndex);
    ($tbody.length ? $tbody : $targetContainer).children().each(function (idx){
      $(this).attr('wbl-curr-row-index', idx);
    });

    var $addRowBtns = typeof $addRow === 'function' ? $addRow($targetContainer) : $addRow;
    $addRowBtns.on('click', function (){
      if (validateAddRow()){
        addRow(true);
      }
    });
    $addRowBtns.on('addRow', function (){
      if (validateAddRow()){
        addRow(false);
      }
    });

    function validateAddRow(){
      //validate
      if (validationFn && typeof validationFn === 'function'){
        return validationFn();
      }

      return true; //no validation needed
    }

    function addRow(focus){
        // Clones.
      var $instance = cloneRow($targetContainer, $rowTemplateContainer);

      if (rowAddedFn && typeof rowAddedFn === 'function'){
        rowAddedFn($instance);
      }
      
      // Custom setup.
      $targetContainer.triggerHandler("row-added", [$instance]);

      if (focus){
        // Sets focus.
        $instance.find('input[type=text]:visible:not([readonly]):not([disabled]),select:visible:not([disabled]),textarea:visible:not([readonly])').not('.no-autofocus').first().focus();
      }
    }
  });
}

function cloneRow($targetContainer, $rowTemplateContainer, rowIndex){
  var $rowTemplateTbody = $rowTemplateContainer.find('> tbody'),
    $instance = ($rowTemplateTbody.length ? $rowTemplateTbody : $rowTemplateContainer).children(":first").clone(),
    lastIndexName = 'wbl-last-row-index',
    currIndexName = 'wbl-curr-row-index';
  if (!rowIndex){
    rowIndex = Number($targetContainer.attr(lastIndexName) || 0) + 1;
    $targetContainer.attr(lastIndexName, rowIndex);
    $instance.attr(currIndexName, rowIndex);
  }

  // Sets index on name recursively all the way up.
  var grandParentIndices = $targetContainer.parents('[' + currIndexName + ']').map(function (){
      return $(this).attr(currIndexName);
  }).toArray(),
  parentIndices = grandParentIndices.concat(rowIndex);
  $instance.find('[name*="[]"]').each(function (){
    var $input = $(this);
    parentIndices.forEach(function (val){
        $input.attr('name', $input.attr('name').replace('[]', '[' + val + ']'));
    });
  });
  $instance.find('[wblgstc-link-params*="[]"]').each(function (){
    var $input = $(this);
    parentIndices.forEach(function (val){
        //$input.attr('wblgstc-link-params', $input.attr('wblgstc-link-params').replace('[]', '[' + val + ']'));
        $input.attr('wblgstc-link-params', $input.attr('wblgstc-link-params').split('[]').join('[' + val + ']'));
    });
  });

  // Adds.
  var $tbody = $targetContainer.find('> tbody');
  ($tbody.length ? $tbody : $targetContainer).append($instance);

  return $instance;
}

$('table,.subitem-container')
.on('click', '[remove-row]', function (e){
  var $tr = $(this).closest('tr,.panel,.rowpanel'),
      $table = $tr.closest('table,.subitem-container');
  $table.triggerHandler("row-removing", [$tr]);
  // $tr.remove();
  $table.triggerHandler("row-removed", [$tr]);

  $table.attr('data-has-changed', '1');
}).on('click', '[deliver-row]', function(e){
  var $tr = $(this).closest('tr,.panel,.rowpanel'),
      $table = $tr.closest('table,.subitem-container');
  
  $table.triggerHandler("row-delivering", [$tr]);
  $table.attr('data-has-changed', '1');
}).on('click', '[counter-up]', function(e){
  var $tr = $(this).closest('tr,.panel,.rowpanel'),
      $table = $tr.closest('table,.subitem-container');
  
  $table.triggerHandler("row-counterup", [$tr]);
  $table.attr('data-has-changed', '1');
}).on('click', '[counter-down]', function(e){
  var $tr = $(this).closest('tr,.panel,.rowpanel'),
      $table = $tr.closest('table,.subitem-container');
  
  $table.triggerHandler("row-counterdown", [$tr]);
  $table.attr('data-has-changed', '1');
}).on("keyup keydown change", '[sub-input]', function(e){
  var $tr = $(this).closest('tr,.panel,.rowpanel'),
      $table = $tr.closest('table,.subitem-container');
  $table.triggerHandler("row-updating", [$tr]);
  $table.attr('data-has-changed', '1');
});

function inputSearch(inputId, urlSearch, width, callBack)
{
  let input = $(inputId);
  input.select2({
    theme: 'bootstrap',
    allowClear: true,
    placeholder: 'Cari...',
    width: width,
    ajax: {
      url: urlSearch,
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results:  $.map(data, function (item) {
            return callBack(item)
          })
        };
      },
      cache: false
    }
  })
}

function changeOptSelect2($select, url)
{
  $.ajax({
      type: 'GET',
      url: url
  }).then(function (data) {
      var option = new Option(data[0].text, data[0].id, true, true);
      $select.append(option).trigger('change');
  });
}


$.fn.setupMask = function (precision){
  $(this).each(function (){
    var $input = $(this);

    var html = '<input type="text" class="form-control input-sm text-right masking" />'; 
    var $mask = $(html).insertAfter($input);
    
    $input
      .blur(function (){
        if ($input.data('programmaticallyfocus')) return;

        $(this).toggleClass('d-none');
        $mask.toggleClass('d-none');
      })
      .change(function (){
        inputChange($(this));
      })
      .on('requestUpdateMask', function (){
      inputChange($(this));
      })
      .on('disabledMask', function (event, bool){
        $mask.prop('disabled', bool);
      })
      .on('readOnlyMask', function (event, bool){
        $mask.prop('readOnly', bool);
      });;
      
      $mask
      .focus(function (){
        if (!$input.prop('readonly')){
          $(this).toggleClass('d-none');
          $input.toggleClass('d-none');

          // Firefox @!&*^@!#^
          $input.data('programmaticallyfocus', true);
          $input.focus();
          $input.select();
          $input.removeData('programmaticallyfocus');
        }
      });

    $mask.attr('required', $input.prop('required'));
    $mask.prop('disabled', $input.prop('disabled'));
    $mask.prop('required', $input.prop('required'));
    if ($input.prop('autofocus')){
      setTimeout(function (){
        $mask.focus();
      });
    }

    // Initial state to show value.
    inputChange($input);
    $input.addClass('d-none');

    function inputChange($self){
      var valueText = $self.val(),
        value = valueText ? Number(valueText) : null;
      value = isNaN(value) ? null : value;
      $mask.val(value === null ? null : value.toLocaleString(undefined, { minimumFractionDigits: precision === undefined ? (value % 1 === 0 ? 0 : 2) : precision }));

      ['readonly', 'disabled', 'required', 'min', 'max', 'placeholder'].forEach(function (val){
        copyAttr(val);
      });

      function copyAttr(attr){
        if (!!$self.attr(attr)) {
          $mask.prop(attr, $self.prop(attr));
        } else {
          $mask.removeAttr(attr);
        }
      }
    }
  });
}

function showPopupForm($btn, options, title, $popup, postUrl, getPostDataFn, successCallbackFn, failCallbackFn){
  var content = $('<form></form>').append($popup.html());
  options.noClickOutside = true;
  var modal = showModal(title, content, options, function (e)
  {
    var $form = e.modalBody.find('form');
    // //console.log($form)
    // if (!$form.valid()) return;

    var url = typeof postUrl === 'function' ? postUrl($form) : postUrl,
      postData = getPostDataFn($form),
      actualPostData = Object.assign({}, postData);
    delete actualPostData.tempData;

    $.post(url, actualPostData, function (data){
      $btn.prop('disabled', false);
      // Closes current modal.
      e.close();

      if (!data) return;
      if (!data.status == 'success'){
        if (failCallbackFn){
          data.previousPostUrl = url;
          data.postData = postData;
          failCallbackFn(data);
        } else {
          //console.log('e',data);
        }
      } else {
        successCallbackFn(data);
      }
    });
  });
  return modal;
}

function showModal(title, content, options, callback){
  $modal = cloneModal($('#cafeModal'));
  $modal.modal({
    show: false,
    backdrop: options.noClickOutside ? 'static' : true,
    keyboard: options.noClickOutside ? false : true
  });
  
  // Draws text.
  var $modalTitle = $modal.find('.modal-title');
  $modalTitle.html(title);
  var $modalBody = $modal.find('.modal-body');
  $modalBody.html(content);
  $modal.find('.modal-action-cancel').removeClass('d-none');
  $actionBtn = $modal.find('.modal-action-yes');
  if (options.caption){
    $actionBtn.text(options.caption);
  }

  $actionBtn
    .removeClass('d-none')
    .addClass('btn-' + ((typeof options === 'object' ? options.btnType : options) || 'primary'));
  if (callback){
    $modal.find('.modal-action-yes').click(function (){
      callback({ 
        confirmBtn: $(this), 
        modalTitle: $modalTitle, 
        modalBody: $modalBody,
        options: options,
        close: function (){
          $modal.modal('hide');
          $('#uiModalInstance').remove();
          $('.modal-backdrop').remove();
        }
      });
      if (!options || typeof options !== 'object' || !options.keepOpen){
        $modal.modal('hide');
      }
    });
  }

  $modal.modal('show');
  if (options.noAutoFocus){
    setTimeout(function (){
      $modalBody.find('input[type=text]:visible:not([readonly]):not([disabled]),select:visible:not([disabled]),textarea:visible:not([readonly])').first().focus();
    }, 500);
  }

  return {
    modalTitle: $modalTitle, 
    modalBody: $modalBody,
    actionBtn: $actionBtn
  };
}

window.inputNumber = function(el) {

  var min = el.attr('min') || false;
  var max = el.attr('max') || false;

  var els = {};

  els.dec = el.prev();
  els.inc = el.next();

  el.each(function() {
    init($(this));
  });

  function init(el) {

    els.dec.on('click', decrement);
    els.inc.on('click', increment);

    function decrement() {
      var value = el[0].value;
      value--;
      if(!min || value >= min) {
        el[0].value = value;
      }
    }

    function increment() {
      var value = el[0].value;
      value++;
      if(!max || value <= max) {
        el[0].value = value++;
      }
    }
  }
}