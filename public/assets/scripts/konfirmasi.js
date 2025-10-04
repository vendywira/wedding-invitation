const jsonUrl = "db/data.json";
const konfirmasiUrl = "konfirmasi.php";
const listKonfirmasiURL = "listkonfirmasifront.php";

var konfirmasi = {
  orderKonfirmasi: 'new',
  showAll : false,
  loadingform: function(status){
    if(status == 'show'){
      $("#konfirmasi").addClass('invisible');
      $("#loadingkonfirmasiform").removeClass('invisible');
    }else{
      $("#konfirmasi").removeClass('invisible');
      $("#loadingkonfirmasiform").addClass('invisible');
    }    
  },
  clearFormData: function(){
    $('#nama-fm').val("");
    $('#jumlah-fm').val("");
    $('#kehadiran-fm').val("");
    $('#pesan-fm').val("");
  },
  saveKonfirmasi : function(){
    var jsonInsert = {
      "name": $('#nama-fm').val(),
      "jumlah": $('#jumlah-fm').val(),
      "kehadiran": $('#kehadiran-fm').val(),
      "doa": $('#pesan-fm').val(),
      "konfirmasi": 1
    };
    konfirmasi.loadingform('show');

    $.post(baseurl+konfirmasiUrl, jsonInsert, function(response) {
        if(response.status === 'success'){
          $('#successModal').modal('show');
          konfirmasi.showAll = false;
          konfirmasi.orderKonfirmasi = 'new';
          konfirmasi.showKonfirmasiData();
          konfirmasi.clearFormData();
          konfirmasi.loadingform('hide');
        }else{
          konfirmasi.loadingform('hide');
          konfirmasi.clearFormData();
        }        
        
    });

  },
  showKonfirmasiData: function(){
    var addtionalParameter = "?";
    addtionalParameter+="sort="+konfirmasi.orderKonfirmasi;
    if(konfirmasi.showAll){
      addtionalParameter+="&showall=yes";
    }

    $("#listkomentar").load(baseurl+listKonfirmasiURL+addtionalParameter,function(){
      var totalComment = $("#totalcomment").val();
      var sisaComment = $("#sisacomment").val();

      if(typeof totalComment !== "undefined" && typeof sisaComment !== "undefined"){
        $("#totalDoa-view").html(totalComment+" ucapan");
        if(parseInt(sisaComment) > 0){
          $('#showallcomment-btn').html('Tampilkan Semua ('+sisaComment+')').removeClass('invisible');
        }else{
          $('#showallcomment-btn').html('Tampilkan Semua ('+sisaComment+')').addClass('invisible');
        }
      }
      
    }).fadeIn("slow");
  },
  showallComment: function(){
    konfirmasi.showAll = true;
    konfirmasi.showKonfirmasiData();
  },
  orderAllComment: function(sort){
    konfirmasi.showAll = false;
    konfirmasi.orderKonfirmasi = sort; // old | new;
    konfirmasi.showKonfirmasiData();
  },
  maxLength: function(el) {    
    if (!('maxLength' in el)) {
        var max = el.attributes.maxLength.value;
        el.onkeypress = function () {
            if (this.value.length >= max) return false;
        };
    }
  }
};



$.validator.addMethod('minStrict', function (value, el, param) {
  return value >= param;
});

$.extend($.validator.messages, {
  minStrict: "Mohon masukan minimum 1 tamu",
  min:"Mohon masukan minimum 1 tamu",
});


$(document).ready(function(){  
  konfirmasi.maxLength(document.getElementById("pesan-fm"));
  
  $("#send-konfirmasi").click(function(){

    $( "#konfirmasi-form" ).validate( {
      rules: {
        "nama-fm": "required",
        "jumlah-fm": {
          required: true,
          minStrict: 1,
          number: true
        },
        "kehadiran-fm": "required",
        "pesan-fm": "required"
      },
      messages: {
        "nama-fm": {
          required: "Mohon memasukan nama"
        },
        "jumlah-fm": {
          required: "Mohon memasukan jumlah tamu undangan",
          minStrict: "Mohon masukan minimum 1 tamu",
          number: "Mohon masukan angka"
        },
        "kehadiran-fm": {
          required: "Mohon pilih konfirmasi kehadiran"
        },
        "pesan-fm": {
          required: "Mohon masukan ucapan/doa"
        }
      },
      errorElement: "em",
      errorPlacement: function ( error, element ) {
        // Add the `help-block` class to the error element
        error.addClass( "help-block" );
  
        if ( element.prop( "type" ) === "checkbox" ) {
          error.insertAfter( element.parent( "label" ) );
        } else {
          error.insertAfter( element );
        }
      },
      highlight: function ( element, errorClass, validClass ) {
        $( element ).parents( ".col-md-12" ).addClass( "has-error" ).removeClass( "has-success" );
      },
      unhighlight: function (element, errorClass, validClass) {
        $( element ).parents( ".col-md-12" ).addClass( "has-success" ).removeClass( "has-error" );
      },
      submitHandler: function(){
        konfirmasi.saveKonfirmasi();
        return false;
      }
    } );
    
  });

  konfirmasi.showKonfirmasiData();

  $("#konfirmasi-display-order").change(function(){
    var selector = $(this).val();
    konfirmasi.orderAllComment(selector);
  });

  $("#showallcomment-btn").click(function(){
    konfirmasi.showallComment();
  });
});