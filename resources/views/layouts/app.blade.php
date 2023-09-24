<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .modal-loader {
            padding: 20px;
            display: none;
        }
        @media (min-width: 1200px) {
           .modal-xlg {
              max-width: 90%; 
           }
        }
        .right { float: right; }
        .left { float: left; }
        .table-orders tbody tr {
            cursor: pointer;
        }
        .table-orders tbody tr:hover {
            background-color: yellow;
        }
            /* https://stackoverflow.com/questions/18023493/bootstrap-dropdown-sub-menu-missing */
            .navbar-nav li:hover > ul.dropdown-menu {
                display: block;
            }
            .dropdown-submenu {
                position:relative;
            }
            .dropdown-submenu>.dropdown-menu {
                top:0;
                left:100%;
                margin-top:-6px;
            }
            /* Adjust position of first level menu popup or it's hard to click */
            .navbar-nav li:hover > ul.dropdown-menu-right { top: 35px; left: 5px; }

        @yield('css')

    </style>
</head>
<body>
    <div id="app">
        @if(!isset($hideMainNav))
            @include('layouts.nav.top')
            {{-- /*@include('layouts.nav.status')*/ --}}
        @endif
        <main class="py-4">
            @include('layouts.flash')
            @yield('content')
        </main>
    </div>

    <!-- Modal -->
    <div class="modal fade inactive" id="mainModal" tabindex="-1" role="dialog" aria-labelledby="mainModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xlg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mainModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-loader">
                    <img src="/img/loader.gif">
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var content = {
            ob: null,
            init: function() {
                this.ob = $('.justify-content-center').find('.card-body')[0];
            },
            load: function(data) {
                $(this.obj).html(data);
            },
        };

        var modal = {
            id: 'mainModal',
            ob: null,
            loading: false,
            init: function() {
                $('#mainModal.active').modal('hide');
                //$('#mainModal.active').remove();
                var m = $('#mainModal.inactive');
                m = $(m).clone();
                $(m).addClass('active');
                $(m).removeClass('inactive');
                this.ob = m;
            },
            setName: function(name) {
                $(this.ob).find('.modal-title').text(name);
            },
            setBody: function(body) {
                $(this.ob).find('.modal-body').html(body);
            },
            getBody: function() {
                return $(this.ob).find('.modal-body');
            },
            modal: function(option) {
                $(this.ob).modal(option);
            },
            setConfirmButtonText: function(str) {
                $(this.ob).find('.modal-footer .btn-primary').text(str);
            },
            setConfirmButtonAction: function(func) {
                $(this.ob).find('.modal-footer .btn-primary').click(func);
            },
            hideConfirmButton: function(func) {
                $(this.ob).find('.modal-footer .btn-primary').hide();
            },
            showConfirmButton: function(func) {
                $(this.ob).find('.modal-footer .btn-primary').show();
            },
            loadAjaxBody: function(data,method = 'POST', doneFunc = false) {
                // Assign handlers immediately after making the request,
                // and remember the jqXHR object for this request
                var body;
                this.startLoading();
                var jqxhr = $.ajax({
                    url: '/ajax',
                    method: method,
                    data: data 
                   })
                  .always(function(data) {
                    //modal.setBody(body);
                    //modal.stopLoading();
                  })
                  .done(function(data) {
                    var div = $('<div></div>');
                    //var test = 
                    var form = $(data).find('.form');
                    console.log(form);
                    body = data;
                    if (doneFunc)
                        doneFunc();
                   // if ($(form).length) {
                   //     console.log($(form));
                   //     modal.setConfirmButtonAction(function(){$("#"+$(form).attr('id')).submit()});
                   // }
                   // console.log('success');
                  //  console.log(data);
                  })
                  .fail(function(data) {
                    body = "<div class='alert alert-danger'>Error: "+data+"</div>";
                    console.log("Error: "+data);
                  });

                 
                // Perform other work here ...
                 
                // Set another completion function for the request above
                jqxhr.always(function() {
                    modal.setBody(body);
                    var form = $(modal.getBody()).find('form');
                    console.log(form);
                    if (form.length > 0) {
                        modal.setConfirmButtonAction(function(){$("#"+$(form).attr('id')).submit()});  
                        modal.showConfirmButton();    
                    } else {
                        modal.hideConfirmButton();
                    }
                    modal.stopLoading();
                });
            },
            startLoading: function() {
                $('#'+this.id).find('.modal-body').hide();
                $('#'+this.id).find('.modal-loader').show();
            },
            stopLoading: function() {
                $('#'+this.id).find('.modal-loader').hide();
                $('#'+this.id).find('.modal-body').show();
            }
        };

        function cancelOrder(orderID) {
            var data = {'controller':'orders','action':'delete','order_id':orderID};
           

            var c = confirm("Are you sure you wish to cancel this order?");
            if (c !== true)
                return false;

            var jqxhr = $.ajax({
                url: '/ajax',
                method: 'POST',
                data: data 
               })
              .always(function(data) {
                //modal.setBody(body);
                //modal.stopLoading();
              })
              .done(function(data) {
                loadOrdersTable(1,1);
              })
              .fail(function(data) {
                alert('error loading data');
              });

             
            // Perform other work here ...
             
            // Set another completion function for the request above
            jqxhr.always(function() {
               
            });           
        }

        function loadModal() {
           // var modal = new modalOb;
            modal.setName('Test');
            modal.modal('show');
        }


        function submitSellOrdersForm(btn) {

            var form = $('#sellOrdersForm');
            var shareID = $(form).find('[name=share_id]').val();
            var qty = $(form).find('[name=qty]').val();
            var exchangeID = $(form).find('[name=exchange_id]').val();
            var price = $(form).find("[name=price]").val();
            var companyID = $(form).find("[name=company_id]").val();
            var companyName = $(form).find("[name=company_name]").val();
            var ttl = $(form).find("[name=total]").val();


            var c = confirm("Are you sure you want to sell "+qty+" shares of "+companyName+" at "+price+" for a total of "+(ttl)+"?");
            if (c !== true) {
                modal.modal('hide');
                return false;
            }

            modal.init();
            modal.loadAjaxBody({'share_id':shareID,'controller':'shares','action':'sell','qty':qty,'price':price,'exchange_id':exchangeID,'company_id':companyID},'POST',function(){loadOrdersTable(exchangeID,companyID);});
            //modal.hideConfirmButton();
            modal.modal('show');
            //loadOrdersTable(exchangeID,companyID);
            //console.log('selling');
            return false;
        }

        function submitBuyOrdersForm(btn) {

            var form = $('#buyOrdersForm');
            var companyID = $(form).find('[name=company_id]').val();
            var companyName = $(form).find("[name=company_name]").val();
            var qty = $(form).find('[name=qty]').val();
            var exchangeID = $(form).find('[name=exchange_id]').val();
            var price = $(form).find("[name=price]").val();
            var ttl = $(form).find("[name=total]").val();

            var c = confirm("Are you sure you want to buy "+qty+" shares of "+companyName+" at "+price+" for a total of "+(ttl)+"?");
            if (c !== true) {
                modal.modal('hide');
                return false;
            }

            modal.init();

            modal.loadAjaxBody({'company_id':companyID,'controller':'shares','action':'buy','qty':qty,'price':price,'exchange_id':exchangeID},'POST',function(){loadOrdersTable(exchangeID,companyID);});
            //modal.hideConfirmButton();
            modal.modal('show');
            //loadOrdersTable(exchangeID,companyID);
            //console.log('selling');
            return false;            
        }

        function loadOrdersTable(exchangeID,companyID) {
            var data = {'exchange_id':exchangeID,'company_id':companyID,'controller':'exchange','action':'view'};
            var table = $('#orderContent');
            $(table).html("<img src='/img/loader.gif>");
$('orderContent').hide();
            var jqxhr = $.ajax({
                url: '/ajax',
                method: 'POST',
                data: data 
               })
              .always(function(data) {
                //modal.setBody(body);
                //modal.stopLoading();
              })
              .done(function(data) {
                $(table).html(data);
                $(table).fadeIn();
              })
              .fail(function(data) {
                alert('error loading data');
              });

             
            // Perform other work here ...
             
            // Set another completion function for the request above
            jqxhr.always(function() {
               
            });
        }

        // (function() {
        //   'use strict';
        //   window.addEventListener('load', function() {
        //     // Fetch all the forms we want to apply custom Bootstrap validation styles to
        //     var forms = document.getElementsByClassName('needs-validation');
        //     // Loop over them and prevent submission
        //     var validation = Array.prototype.filter.call(forms, function(form) {
        //       form.addEventListener('submit', function(event) {
        //         if (form.checkValidity() === false) {
        //           event.preventDefault();
        //           event.stopPropagation();
        //         }
        //         form.classList.add('was-validated');
        //       }, false);
        //     });
        //   }, false);
        // })();

        function addToOrderBox(tr) {
            var sum = $(tr).attr('data-sum-price');
            var sumQty = $(tr).attr('data-sum-qty');
            var price = $(tr).attr('data-price');
            var qty = $(tr).attr('data-qty');
            var action = $(tr).attr('data-action');
            var id = $(tr).attr('id').replace('row','');
            var tbody = $(tr).parents('tbody');

            var orderForm = $(tr).parents('.card-orders').find('.orderForm');
            $(orderForm).find('[name=price]').val(price);
            $(orderForm).find('[name=qty]').val(sumQty);
            // reverse to do the other form
            if (action == "buy")
                action = "sell";
            else
            if (action == 'sell')
                action = 'buy';
            // ttl = parseFloat( $(tbody).find('#row'+(id-1)).attr('data-sum-price') );
            // if (!ttl)
            //     ttl = 0;
            // console.log(ttl);
            // ttl += ( parseFloat( price ) * parseFloat( qty ) );
            // console.log(ttl);
            $(orderForm).find('[name=total]').val(sum);
//            calculateTotalOrder(action);
        }

        @yield('javascript')

        $(document).ready(function(){
            @yield('on-load')
        });
    </script>
</body>
</html>
