<script type="text/javascript">
    var map, infoWindow;
    var directionsService;
    var directionsDisplay;
    var stepDisplay;
    var markerArray = [];
    var card;
    var pickupLocation, destinationLocation;



    $(document).ready(function () {
        //tooltip viewer
        $('[data-toggle="tooltip"]').tooltip();

        //change div
        $("#estimationPrice").click(function () {
            var pickup = $("#pickup").val();
            var destination = $("#destination").val();
            jQuery.ajax({
                type: "POST",
                url: "http://localhost/team39/Stuurboi/requests/calculateDistance",
                dataType: 'text',
                data: {pickup: pickup, destination: destination},
                success: function (res) {

                    var carType = $("#hiddenCar").val();
                    var estimation = 0;
                    if (carType == 'truck') {
                        estimation = res * (0.09);
                    } else if (carType == 'bike') {
                        estimation = res * (0.02);
                    } else if (carType == 'bakkie') {
                        estimation = res * (0.06);
                    } else if (carType = 'car') {
                        estimation = res * (0.04);
                    }
                    $("#hiddenPrice").val(estimation);
                    document.getElementById('estPrice').innerHTML = 'R ' + estimation.toFixed(2);
                }
            });
        });
    });


    //request map
    function initMap() {
        //**********************************************seting map************************************
        //
        //
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -26.183, lng: 27.994}, // uj student center default location  
            zoom: 16,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                position: google.maps.ControlPosition.TOP_RIGHT
            }

        });

        new AutocompleteDirectionsHandler(map);
        //*************************************getting current location***********************************
        //
        //
        infoWindow = new google.maps.InfoWindow;
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                infoWindow.setPosition(pos);
                infoWindow.setContent('You are here.');
                infoWindow.open(map);
                map.setCenter(pos);
            }, function () {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
        //***********************************************Textbox place Autocomplete************************************
        //
        //

    }

    function AutocompleteDirectionsHandler(map) {
        this.map = map;
        this.originPlaceId = null;
        this.destinationPlaceId = null;
        this.travelMode = 'DRIVING';
        var originInput = document.getElementById('pickup');
        var destinationInput = document.getElementById('destination');
        this.directionsService = new google.maps.DirectionsService;
        this.directionsDisplay = new google.maps.DirectionsRenderer;
        this.directionsDisplay.setMap(map);

        var originAutocomplete = new google.maps.places.Autocomplete(
                originInput, {placeIdOnly: true});
        var destinationAutocomplete = new google.maps.places.Autocomplete(
                destinationInput, {placeIdOnly: true});

        this.setupPlaceChangedListener(originAutocomplete, 'ORIG');
        this.setupPlaceChangedListener(destinationAutocomplete, 'DEST');

        // this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(originInput);
        // this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(destinationInput);
    }


    AutocompleteDirectionsHandler.prototype.setupPlaceChangedListener = function (autocomplete, mode) {
        var me = this;
        autocomplete.bindTo('bounds', this.map);
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.place_id) {
                window.alert("Please select an option from the dropdown list.");
                return;
            }
            if (mode === 'ORIG') {
                me.originPlaceId = place.place_id;
            } else {
                me.destinationPlaceId = place.place_id;
            }
            me.route();
        });
    };

    AutocompleteDirectionsHandler.prototype.route = function () {
        if (!this.originPlaceId || !this.destinationPlaceId) {
            return;
        }
        var me = this;
        this.directionsService.route({
            origin: {'placeId': this.originPlaceId},
            destination: {'placeId': this.destinationPlaceId},
            travelMode: this.travelMode
        }, function (response, status) {
            if (status === 'OK') {
                me.directionsDisplay.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    };

    function E1_Countradioboxes()
    {
        var count = 0;
        if (document.getElementById("id_car").checked === true)
        {
            count++;
        }
        if (document.getElementById("id_bike").checked === true)
        {
            count++;
        }
        if (document.getElementById("id_bakkie").checked === true)
        {
            count++;
        }
        if (document.getElementById("id_truck").checked === true)
        {
            count++;
        }

        return count;
    }
    function vehicle_validate()
    {

        var countval = E1_Countradioboxes();
        if (countval >= 1)
        {

            if (document.getElementById("id_car").checked === false)
            {
                document.getElementById("id_car").disabled = true;
            }
            if (document.getElementById("id_bike").checked === false)
            {
                document.getElementById("id_bike").disabled = true;
            }
            if (document.getElementById("id_bakkie").checked === false)
            {
                document.getElementById("id_bakkie").disabled = true;
            }
            if (document.getElementById("id_truck").checked === false)
            {
                document.getElementById("id_truck").disabled = true;

            }

        } else
        {
            document.getElementById("id_car").disabled = false;
            document.getElementById("id_bike").disabled = false;
            document.getElementById("id_bakkie").disabled = false;
            document.getElementById("id_truck").disabled = false;

        }
    }

</script>

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbskBXR0Kfei73dGkmDbx2DVJx-_Pft54&libraries=places&callback=initMap">
</script>
<form action="<?php echo base_url('requests/request') ?>" method="POST">

    <div class="col-md-9 " >
        <!--  MAP VIEW -->
        <div class="row col-xs-12" id="pac-container">
            <!--Div for showing map -->
            <div id="map" class="col-md-10 col-xs-12" style="height: 350px;z-index:1;position:absolute;"></div>
            <!--map to and from text boxes -->
            <input type="text" id="pickup" class="col-md-4" style="z-index: 2; position:absolute;margin: 10px;height:40px; " name="fromAddress" placeholder="Pickup Location" required="" />
            <input type="text"  id="destination" class="col-md-4" style="z-index: 2; position:absolute;margin: 51px 10px 0px 10px;height:40px; " name="toAddress" placeholder="Destination Location" required="" />

        </div>
    </div>
    <!-- Info bar-->
    <div class="col-md-7 infobar " >
        <div>
            <div class="col-md-5 col-xs-6" id="right-panel" style="border-right:2px #cccccc ridge;padding-right: 45px;">
                <p data-toggle="tooltip" data-placement="top" title="This should be the number of the reciepient or person we collect from">Receiver Information<span class="glyphicon glyphicon-info-sign"></span></p>
                <input type="text" class="col-xs-9" name="receiverCell" style="margin: 10px 10px 10px 10px;height:35px;padding-left:5px;" placeholder="Reciever's Number" required="" ><br/>
                <input type="text" class="col-xs-9"  name="receiverName" style="margin: 10px 10px 10px 10px;height:35px;padding-left:5px;" placeholder="Reciever's Name" required="" ><br/>

            </div>
            <div class="col-md-6 col-xs-6" >

                <h5><b> Select the appropriate package state </b></h5>
                <input type="radio" class="PackageState" value="<?php echo Constants::BOOLEAN_TRUE; ?>"  name="fragile"> Fragile 
                <input type="radio" class="PackageState" value="<?php echo Constants::BOOLEAN_FALSE; ?>" name="fragile"> Non-Fragile 

                <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
            </div>

            <div></div>

            <div class="col-md-6 col-xs-6" >


                <lable><b>Select Vehicle type</b></lable><br/>


                <input type="radio" class="carType img-circle" id="id_car" value="<?php echo Constants::TRANSPORT_CAR; ?>" name="vehicleType" > Small/Medium
                <img src="<?php echo base_url('res/images/vehicleImages/car.png') ?>"  alt="car" value="car" height="42" width="42">


                <input type="radio" name="vehicleType"  class="carType img-circle" id="id_bike" value="<?php echo Constants::TRANSPORT_MOTORBIKE; ?>"  > Small 
                <img src="<?php echo base_url('res/images/vehicleImages/bike.png'); ?>"  alt="Bike" value="bike" height="42" width="42"> 


               <br/> <input type="radio" name="vehicleType" class="carType img-circle" id="id_bakkie" value="<?php echo  Constants::TRANSPORT_BAKKIE; ?>"  > Medium/Large
                <img src="<?php echo base_url('res/images/vehicleImages/bakkie.png') ?>"  alt="bakkie"  value="bakkie" height="42" width="42"> 


                <input type="radio" name="vehicleType" class="carType img-circle" id="id_truck" value="<?php echo Constants::TRANSPORT_TRUCK; ?>"  > Large
                <img src="<?php echo base_url('res/images/vehicleImages/truck.png') ?>"  alt="truck" value="truck" height="42" width="42" </label>
            </div>
        </div>
        <input type="hidden" value="" name="estimationPrice" id="hiddenPrice">
        <div class="row"> </div>
        <table class="col-md-6 col-md-offset-3 " >

            <tr>
            <lable class="col-md-offset-5 small">Select Payment Type</lable>
            </tr>
            <tr >
                <td>
                    <a href="<?php echo base_url('payments/paymentMethods'); ?>" class="paymentType"  name="paypal"> <img src="<?php echo base_url('res/images/PaymentOptionIcon/paypal.png'); ?>" alt="paypal" value="paypal" height="42" width="42"/></a>
                </td>
                <td>
                    <a href="<?php echo base_url('payments/paymentMethods'); ?>" class="paymentType"  name="mastercard"><img src="<?php echo base_url('res/images/PaymentOptionIcon/mastercard.png') ?>" alt="mastercard" value="mastercard" height="42" width="42"></a>
                </td>
                <td>
                    <a href="<?php echo base_url('payments/paymentMethods'); ?>" class="paymentType"  name="visa"><img src="<?php echo base_url('res/images/PaymentOptionIcon/visa.png') ?>" alt="visa"  value="visa" height="42" width="42"></a>
                </td>
                <td>
                    <a href="<?php echo base_url('payments/paymentMethods'); ?>" class="paymentType"  name="americanexpress"><img src="<?php echo base_url('res/images/PaymentOptionIcon/americanexpress.png') ?>" alt="americanexpress" value="americanexpress" height="42" width="42"></a>
                </td>
                <td>
                    <a href="<?php echo base_url('payments/paymentMethods'); ?>" class="paymentType"  name="discover"><img src="<?php echo base_url('res/images/PaymentOptionIcon/discover.png') ?>" alt="discover" value="discover" height="42" width="42"></a>
                </td>

            </tr>
        </table>
        <div class="row"></div>
        <p  >
            <a class="btn btn-primary " id="estimationPrice"  data-toggle="collapse" href="#collapseEstimateprice" role="button" aria-expanded="false" aria-controls="collapseExample">
                Estimation Price   
            </a>
        </p>
        <div class="collapse" id="collapseEstimateprice">
            <div id="estPrice" class="card card-body">

            </div>
        </div>

        <input type="submit" name="btnRequest" class="btn btn-block"  value="Request"/>
    </div>
</form>

