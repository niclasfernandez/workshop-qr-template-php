<!DOCTYPE html>
<html>
    <head>
        <title>Ejemplo e-commerce</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=1024">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="format-detection" content="telephone=no">
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
        
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <link rel="stylesheet" href="/assets/custom.css" type="text/css">
    </head>

    <body>
        <div id="page-container">
            <div class="header-container">
                <img src="/assets/images/banner.jpg"/>
            </div>
            <div id="content-wrap">
                <br>  
                <div class="col-lg-8 main-section p-3 bg-white">
                <div class="row m-0">
                    <div class="col-lg-6 left-side-product-box pb-3">
                        <img id="product_img" src="<?php echo $_POST['img'] ?>" class="border p-3">
                    </div>
                    <div class="col-lg-6">
                        <div class="right-side-pro-detail border p-3 m-0">
                            <div class="row">
                                <div class="col-lg-12">
                                    <p id="product_title" class="m-0 p-0"> <?php echo $_POST['title'] ?> </p>
                                </div>
                                <div class="col-lg-12">
                                    <p id="product_price" class="m-0 p-0 price-pro"> <?php echo $_POST['price'] ?> </p>
                                    <hr class="p-0 m-0">
                                </div>
                                <div class="col-lg-3">
                                    <br><h6>Cantidad :</h6>
                                    <input id="product_quantity" type="number" class="form-control text-center w-100" value="1">
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button id="create_button" onclick="createOrder()" class="mp-button mp-button-create">Generar Orden</button>
                                            <button hidden id="cancel_button" onclick="cancelOrder()" class="mp-button mp-button-cancel">Cancelar Orden</button>
                                            <br><br>
                                            <h5 id="order_status"></h5><!--Actualice aquí el estado de la órden-->
                                            <hr>
                                            <img src="https://www.mercadopago.com/instore/merchant/qr/5891010/4d913321959647a1a1acdaa812ac43d12e6d2daff67840d38c40d320525cd4c9.png" class="border p-3" style="width: 80%;"><!--Completar src con URL de imagen de tu QR de pago-->
                                            <br><br>
                                            <input hidden id="external_reference_id"></input><!--Se sugiere guardar aquí el id de la órden generada-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <br><br>
            </div>
            <footer id="footer" class="bg-secondary">  
                <div class="card-body text-left">
                    <img src="/assets/images/logo.png" style="width: 100px"/>
                    <h6 style="color: white; display:inline"> | Ejemplo de Integración</h6>
                </div>
            </footer>
        </div>

        <script>
            var pollInterval = null;
            
            function createOrder() {
                var ordersUrl = "https://niclas-mp-commerce-php.herokuapp.com/api/orders.php";
                var title = "<?php echo $_POST['title'] ?>";
                var unit_price = <?php echo $_POST['price'] ?>;

                var quantity = document.getElementById('product_quantity').value;

                $.ajax({
                    url: ordersUrl,
                    type: "POST",
                    data: "title="+title+"&unit_price="+unit_price+"&quantity="+quantity,
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded',
                    success: function(data) {
                        document.getElementById('create_button').setAttribute('hidden', false);
                        document.getElementById('cancel_button').removeAttribute('hidden');
                        var external_reference = JSON.parse(data).external_reference;
                        var poll_url = "https://niclas-mp-commerce-php.herokuapp.com/api/status.php?external_reference="+external_reference;

                        var poll = function() {
                            $.ajax({
                                url: poll_url,
                                dataType: 'json',
                                type: 'get',
                                success: function(data) {

                                    var status = data.status;

                                    if (status !== null) {
                                        document.getElementById("order_status").innerText = status;
                                    }
                                    if (status === "closed") {
                                        clearInterval(pollInterval);
                                        document.getElementById('cancel_button').setAttribute('hidden', false);
                                        document.getElementById('create_button').removeAttribute('hidden');
                                    }
                                }
                            });
                        };
                        pollInterval = setInterval(poll, 1000);
                    }
                });
            };

            function cancelOrder() {
                clearInterval(pollInterval);
                var ordersUrl = "https://niclas-mp-commerce-php.herokuapp.com/api/delete-order.php";
                document.getElementById("order_status").innerText = "Erasing";
                $.ajax({
                    url: ordersUrl,
                    type: "POST",
                    success: function(data) {
                        document.getElementById('cancel_button').setAttribute('hidden', false);
                        document.getElementById('create_button').removeAttribute('hidden');
                        setTimeout(() => {
                            document.getElementById("order_status").innerText = "Erased";
                        }, 1500);
                    }
                });
            };
        </script>
    </body>
</html>