<script>
    
$(document).ready(function() {
    $.ajax({
        type: "POST",
        url: "map/geo",
        data: { "_token": "{!! csrf_token() !!}" }
    }).done(function (data) {
        var e = document.createElement("script");
        e.src = data;
        e.type="text/javascript";
        e.setAttribute("async", "true");
        document.getElementsByTagName("head")[0].appendChild(e);
    });
});
        
$(function(){ //load states list for selected country
    $("#country_id").change(function () {  
        $("#city_id").html( $('<option value="0">- ' + '{{ trans("map.sel-city") }}' + ' -</option>'));
        if ($("#country_id option:selected").val() == 0) {
            $("#state_id").html( $('<option>- ' + '{{ trans("map.sel-state") }}' + ' -</option>'));
            return;
        }

        $("#city_id").attr("disabled", true);
        $("#state_id").attr("disabled", true);
        $("#state_id").html('<option>' + '{{ trans("map.loading") }}' + '</option>');
        $("body").css("cursor", "progress");
        
        $.get(
            "map/state",
            { country_id: $("#country_id option:selected").val() },
            function (result) {
                var options = '<option>- ' + '{{ trans("map.sel-state") }}' + ' -</option>';
                $(result).each(function() {
                    options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('state') + '</option>';
                });
                $("#state_id").html(options);
                $("#state_id").attr("disabled", false);
                $("#city_id").attr("disabled", false);
                $("body").css("cursor", "default");
            },
            "json"
        );
    });
});

$(function(){ //load cities list for selected state
    $("#state_id").change(function () { 
        if ($("#state_id option:selected").val() == 0) {
            $("#city_id").html( $('<option value="0">- ' + '{{ trans("map.sel-city") }}' + ' -</option>'));
            return;
        }

        $("body").css("cursor", "progress");
        $("#city_id").attr("disabled", true);
        $("#city_id").html('<option>' + '{{ trans("map.loading") }}' + '</option>');
        $("#country_id").attr("disabled", true);
        
        $.get(
            "map/city",
            { state_id: $("#state_id option:selected").val() },
            function (result) {
                var options = '<option value="0">- ' + '{{ trans("map.sel-city") }}' + ' -</option>';
                $(result).each(function() {
                    options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('city') + '</option>';
                });
                $("#city_id").html(options);
                $("#city_id").attr("disabled", false);
                $("#country_id").attr("disabled", false);
                $("body").css("cursor", "default");
            },
            "json"
        );
    });
});

$(function(){
    $("#city_id").change(function () { //markers for the selected city
        if ($("#city_id option:selected").val() == 0) {
            return;
        }
        
        $("body").css("cursor", "progress");
        $("#country_id").attr("disabled", true);
        $("#state_id").attr("disabled", true);
        $("#city_id").attr("disabled", true);
        
        // get address list for agents in the selected city
        getAddresses($("#city_id option:selected").val(), function(result){
            if (result == "NO_RECORDS") {
                $("#country_id").attr("disabled",false);
                $("#state_id").attr("disabled", false);
                $("#city_id").attr("disabled", false);
                $("body").css("cursor", "default");
                alert("{{ trans('map.no-agent') }}");
                return;
            }
            addr = JSON.parse(result);
        });
        
        var addr;
        var map;
        var latlng = new google.maps.LatLng(0,0);
        var geocoder = new google.maps.Geocoder();
        var marker = [];
        var markers = [];
        
        promises_ary = [];
        addr.forEach(function(obj, i) { // get coords for all addresses
            promise = (function(i) {
                var dfd;
                dfd = new $.Deferred();

                if (addr[i]["geocodeAddr"] !== "") {
                    geocoder.geocode({'address': addr[i]["geocodeAddr"]}, function(coord, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            latlng = coord[0].geometry.location;
                            
                            cacheSave(addr[i]["hash"], latlng.lat(), latlng.lng());
                            addr[i]["latlng"] = latlng;
                            return dfd.resolve();
                        } else {
                            latlng = (0,0);
                        }
                    }); // end geocoder
                } else {
                    latlng = new google.maps.LatLng(addr[i]["lat"],addr[i]["lng"]);
                    addr[i]["latlng"] = latlng;
                    return dfd.resolve();
                }

                return dfd.promise();
            })(i);

            promises_ary.push(promise);      
        });
        
        $.when.apply($, promises_ary).done(function() {
            var mapOptions = { // show city map
                zoom: 11,
                center: addr[0]["latlng"]
            }
            map = new google.maps.Map(document.getElementById("map"), mapOptions);
            
            addr.shift();
            addr.forEach(function(obj, i) { // add markers to the map
                var marker = new google.maps.Marker({
                    map: map,
                    title: addr[i]["address"], 
//TODO / add smth else to the title
                    position: addr[i]["latlng"]
                });
            });
        });
                
        $("#country_id").attr("disabled",false);
        $("#state_id").attr("disabled", false);
        $("#city_id").attr("disabled", false);
        $("body").css("cursor", "default");
        
    }); //END $("#city_id").change(function
});

function getAddresses(city, callback) {
    $.ajax({ 
        type: "GET",
        url: "map/marker",
        async: false,
        data: "city_id=" + city,
        datatype: "json",
        success: function(result) {
            callback(result);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            callback("NO_RECORDS");
        }
    });   
}

function cacheSave(hash, lat, lng) { // saves lat lng to cache
    $.ajax({ 
        type: "GET",
        url: "map/hash",
        data: "hash="+hash+"&lat="+lat+"&lng="+lng,
        success: function() {
            //alert("succ");
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //alert(xhr.status);
        }
    });
}

</script>