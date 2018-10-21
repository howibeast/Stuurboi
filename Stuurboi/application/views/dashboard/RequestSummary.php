

<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="<?php echo base_url("/node_modules/socket.io-client/dist/socket.io.js"); ?>"></script>
<style>
    @media screen and (max-width: 900px) {
        .rowinfo{

            background:white;
            margin-top: 350px; 
            margin-left:2px;
            width:50px;
        }
        .rowinfo1{

        }

        #colorstar { color: #ee8b2d;}
        .badForm {color: #FF0000;}
        .goodForm {color: #00FF00;}
        .evaluation { margin-left:30px;} 
    </style>
    <script type="text/javascript">

        var socket = io('127.0.0.1:3000');
        var tripId = 0;
        socket.on('show trip', function (tripIds) {
            tripId = tripIds;
            jQuery.ajax({
                type: "POST",
                url: "http://localhost/team39/Stuurboi/trips/tripSummary",
                dataType: 'json',
                data: {tripId: tripId},
                success: function (res) {
                    var trip = res.tripInfo;
                    $('.rowinfo').html(
                            '<h2>Trip Summary</h2>\n\
                        <span class="row">\n\
                            <b>Drivers Name: </b> ' + trip.name + ' ' + trip.surname + ' \n\
                        </span>\n\
                        <span class="row">\n\
                            <b>Transport: </b> ' + trip.vehicleType + ' -' + trip.vehicleModel + ' ' + trip.licensePlate + ' \n\
                        </span>\n\
                        <span class="row">\n\
                            <b> charged price: </b> ' + trip.fare + '\n\
                        </span>\n\
                        <span class="row">\n\
                            <b>Fragile: </b> ' + trip.fragile + ' \n\
                        </span>\n\
                        '

                            );
                    $('#rateDriver').val(trip.id);
                }
            });
        });
        // Starrr plugin (https://github.com/dobtco/starrr)
        var __slice = [].slice;

        (function ($, window) {
            var Starrr;

            Starrr = (function () {
                Starrr.prototype.defaults = {
                    rating: void 0,
                    numStars: 5,
                    change: function (e, value) {}
                };

                function Starrr($el, options) {
                    var i, _, _ref,
                            _this = this;

                    this.options = $.extend({}, this.defaults, options);
                    this.$el = $el;
                    _ref = this.defaults;
                    for (i in _ref) {
                        _ = _ref[i];
                        if (this.$el.data(i) != null) {
                            this.options[i] = this.$el.data(i);
                        }
                    }
                    this.createStars();
                    this.syncRating();
                    this.$el.on('mouseover.starrr', 'span', function (e) {
                        return _this.syncRating(_this.$el.find('span').index(e.currentTarget) + 1);
                    });
                    this.$el.on('mouseout.starrr', function () {
                        return _this.syncRating();
                    });
                    this.$el.on('click.starrr', 'span', function (e) {
                        return _this.setRating(_this.$el.find('span').index(e.currentTarget) + 1);
                    });
                    this.$el.on('starrr:change', this.options.change);
                }

                Starrr.prototype.createStars = function () {
                    var _i, _ref, _results;

                    _results = [];
                    for (_i = 1, _ref = this.options.numStars; 1 <= _ref ? _i <= _ref : _i >= _ref; 1 <= _ref ? _i++ : _i--) {
                        _results.push(this.$el.append("<span class='glyphicon .glyphicon-star-empty'></span>"));
                    }
                    return _results;
                };

                Starrr.prototype.setRating = function (rating) {
                    if (this.options.rating === rating) {
                        rating = void 0;
                    }
                    this.options.rating = rating;
                    this.syncRating();
                    return this.$el.trigger('starrr:change', rating);
                };

                Starrr.prototype.syncRating = function (rating) {
                    var i, _i, _j, _ref;

                    rating || (rating = this.options.rating);
                    if (rating) {
                        for (i = _i = 0, _ref = rating - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
                            this.$el.find('span').eq(i).removeClass('glyphicon-star-empty').addClass('glyphicon-star');
                        }
                    }
                    if (rating && rating < 5) {
                        for (i = _j = rating; rating <= 4 ? _j <= 4 : _j >= 4; i = rating <= 4 ? ++_j : --_j) {
                            this.$el.find('span').eq(i).removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                        }
                    }
                    if (!rating) {
                        return this.$el.find('span').removeClass('glyphicon-star').addClass('glyphicon-star-empty');
                    }
                };

                return Starrr;

            })();
            return $.fn.extend({
                starrr: function () {
                    var args, option;

                    option = arguments[0], args = 2 <= arguments.length ? __slice.call(arguments, 1) : [];
                    return this.each(function () {
                        var data;

                        data = $(this).data('star-rating');
                        if (!data) {
                            $(this).data('star-rating', (data = new Starrr($(this), option)));
                        }
                        if (typeof option === 'string') {
                            return data[option].apply(data, args);
                        }
                    });
                }
            });
        })(window.jQuery, window);

        $(function () {
            return $(".starrr").starrr();
        });

        $(document).ready(function () {

            var correspondence = ["", "Really Bad", "Bad", "Fair", "Good", "Excelent"];

            $('.ratable').on('starrr:change', function (e, value) {

                $(this).closest('.evaluation').children('#count').html(value);
                $(this).closest('.evaluation').children('#meaning').html(correspondence[value]);

                var currentval = $(this).closest('.evaluation').children('#count').html();

                var target = $(this).closest('.evaluation').children('.indicators');
                target.css("color", "black");
                target.children('.rateval').val(currentval);
                target.children('#textwr').html(' ');
                $('#ratings').val(value);

                if (value < 3) {
                    target.css("color", "red").show();
                    target.children('#textwr').text('What can be improved?');
                } else {
                    if (value > 3) {
                        target.css("color", "green").show();
                        target.children('#textwr').html('What was done correctly?');
                    } else {
                        target.hide();
                    }
                }

            });

            $('#hearts-existing').on('starrr:change', function (e, value) {
                $('#count-existing').html(value);
            });
//////////////////////////////////////////////////////////////////////////////////////////////
            $('.ratings').on("click", function (e) {
                var rateVal = $('#ratings').val();
                var driverId = $('#rateDriver').val();
                jQuery.ajax({
                    type: "POST",
                    url: "http://localhost/team39/Stuurboi/drivers/rateDriver",
                    dataType: 'json',
                    data: {rateVal: rateVal, driverId: driverId, tripId: tripId},
                    success: function (res) {
                        var response = res;
                        alert(response.message);
                    }
                });

            });

        });

        $(function () {
            $('.button-checkbox').each(function () {

                // Settings
                var $widget = $(this),
                        $button = $widget.find('button'),
                        $checkbox = $widget.find('input:checkbox'),
                        color = $button.data('color'),
                        settings = {
                            on: {
                                icon: 'glyphicon glyphicon-check'
                            },
                            off: {
                                icon: 'fa fa-square-o'
                            }
                        };

                // Event Handlers
                $button.on('click', function () {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    $checkbox.triggerHandler('change');
                    updateDisplay();
                });
                $checkbox.on('change', function () {
                    updateDisplay();
                });

                // Actions
                function updateDisplay() {
                    var isChecked = $checkbox.is(':checked');

                    // Set the button's state
                    $button.data('state', (isChecked) ? "on" : "off");

                    // Set the button's icon
                    $button.find('.state-icon')
                            .removeClass()
                            .addClass('state-icon ' + settings[$button.data('state')].icon);

                    // Update the button's color
                    if (isChecked) {
                        $button
                                .removeClass('btn-default')
                                .addClass('btn-' + color + ' active');
                    } else {
                        $button
                                .removeClass('btn-' + color + ' active')
                                .addClass('btn-default');
                    }
                }

                // Initialization
                function init() {

                    updateDisplay();

                    // Inject the icon if applicable
                    if ($button.find('.state-icon').length == 0) {
                        $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                    }
                }
                init();
            });
        });

    </script>

    <div class="col-md-9  rowinfo1 " >
        <!--  MAP VIEW -->
        <div class="row  ">
            <iframe style="z-index:1;position:absolute;"
                    width="700"
                    height="350"
                    frameborder="0" style="border:0"
                    src="https://www.google.com/maps/embed/v1/directions?key=AIzaSyDbskBXR0Kfei73dGkmDbx2DVJx-_Pft54
                    &origin=<?php echo $request->fromAddress; ?>
                    &destination=<?php echo $request->toAddress; ?>
                    &avoid=tolls|highways" allowfullscreen>
            </iframe>
        </div>
    </div>
    <!-- /.row -->
    <div class="col-md-9 "  >
        <div class="col-md-12 rowinfo" style="padding: 5px  40px;background:white;margin-top: 350px; width: 700px;">
            <h2>Request Summary</h2>
            <span class="row">
                <b>Status :</b> <?php echo $request->status; ?>
            </span>
            <span class="row">
                <b>From :</b> <?php echo $request->fromAddress; ?>
            </span>
            <span class="row">
                <b> To :</b><?php echo $request->toAddress; ?>
            </span>
            <span class="row">
                <b>Type of transport :</b> <?php echo $request->vehicleType; ?>
            </span>
            <span class="row">
                <b>Receiver number :</b> <?php echo $request->receiverCell; ?>
            </span>
            <div class="form-group row">
                <a href="<?php echo base_url('requests/cancelRequest/' . $request->id); ?>" class="btn btn-primary">Cancel</a>
            </div>
			<span>
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="pk_test_TYooMQauvdEDq54NiTphI7jx"
                data-amount="<?php echo $request->estimationPrice; ?>"
                data-name="Stripe.com"
                data-description="Widget"
                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                data-locale="auto"
                data-zip-code="true">
            </script>
        </span>
        </div>
        
        <input type="hidden" name="driverId" id="rateDriver" value="" />
        
    </div>
    <div class="col-md-9 "  >
        <div class="col-md-12 rateInfo" style="padding: 5px  40px;background:white;margin-top: 350px; width: 700px;">

            <h3>Rate the driver</h3>
            <div class="row lead evaluation">
                <div id="colorstar" class="starrr ratable" ></div>
                <span id="count">0</span> star(s) - <span id="meaning"> </span>
                <input type="number" name="ratings" style="display:none;" value="" id="ratings">


                <div class='indicators' style="display:none">
                    <div id='textwr'>What went wrong?</div>

                    <span class="button-checkbox">
                        <button type="button" class="btn criteria" data-color="info">Punctuallity</button>
                        <input type="checkbox" value="1" name="punctuallity" class="hidden"  />
                    </span>
                    <span class="button-checkbox">
                        <button type="button" class="btn criteria" data-color="info">Assistance</button>
                        <input type="checkbox" value="1" name="assistance" class="hidden"  />
                    </span>
                    <span class="button-checkbox">
                        <button type="button" class="btn criteria" data-color="info">Knowledge</button>
                        <input type="checkbox" value="1" name="knowledge" class="hidden"  />
                    </span>
                    <br/>
                    <span class="row">
                        <input class="btn  btn-block ratings" type="submit" name="rate" value="rate">
                    </span>

                </div>
            </div>
        </div>
    </div>
