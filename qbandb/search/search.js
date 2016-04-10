function resetFilters () {

    // address
    $('#address').val('');
    $('#address').removeClass('valid invalid');
    $('#addressLabel').removeClass('active');

    // beds
    $('#selectBeds').val(-1);
    $('#selectBeds').material_select();

    // rooms
    $('#selectRooms').val(-1);
    $('#selectRooms').material_select();

    // price
    slider1.noUiSlider.set([0, 400]);

    // rating
    slider2.noUiSlider.set([2, 10]);

    // district
    $('#selectDistricts').val(-1);
    $('#selectDistricts').material_select();

    // type
    $('#selectType').val(-1);
    $('#selectType').material_select();

    // features
    $('#fullKitchen').prop("indeterminate", true);
    $('#laundry').prop("indeterminate", true);
    $('#pool').prop("indeterminate", true);
    $('#gym').prop("indeterminate", true);
    $('#sharedRm').prop("indeterminate", true);
    $('#privateRm').prop("indeterminate", true);
    $('#closeToTransit').prop("indeterminate", true);
}

$(document).ready(function(){
    
    // enable dropdown/select
    $('select').material_select();

    // price slider
    var slider1 = document.getElementById('slider1');

    noUiSlider.create(slider1, {
        start: [0, 400],
        connect: true,
        step: 10,
        margin: 10,
        range: {
            'min': [ 0   ],
            'max': [ 500 ]
        },
        format: wNumb({
            decimals: 0,
            prefix: "$"
        })
    });

    // rating slider
    var slider2 = document.getElementById('slider2');
    
    noUiSlider.create(slider2, {
        start: [2, 10],
        connect: true,
        step: 1,
        margin: 1,
        range: {
            'min': [ 1   ],
            'max': [ 10 ]
        },
        format: wNumb({
            decimals: 0
        })
    });

    resetFilters();

    // reset button
    $('.resetBtn').click(function(){

        resetFilters();

    });

    // search button
    $('.searchBtn').click(function(){

    	// search button click
        var clickBtnValue = $(this).val();

        // address
        var address = document.getElementById('address').value;

        // beds available
        var bedsElem = document.getElementById('selectBeds');
        var beds = bedsElem[bedsElem.selectedIndex].value;

        // rooms
        var roomsElem = document.getElementById('selectRooms');
        var rooms = roomsElem[roomsElem.selectedIndex].value;

        // price
        var price = document.getElementById('slider1').noUiSlider.get();

        // rating
        var rating = document.getElementById('slider2').noUiSlider.get();

        // district
        var districtElem = document.getElementById('selectDistricts');
        var district = districtElem[districtElem.selectedIndex].value;

        // features
        var featureIDs = [  '#fullKitchen',
            				'#laundry',
            				'#pool',
            				'#gym',
            				'#sharedRm',
            				'#privateRm',
            				'#closeToTransit'
        				];

        var features = 	{
        					'full_kitchen' : -1,
        					'laundry' : -1,
        					'pool' : -1,
        					'gym' : -1,
        					'shared_room' : -1,
        					'private_room' : -1,
        					'close_to_transit' : -1
        				};
        
        var count = 0; // used to increment through featureIDs
        for (var key in features) { // go through features, check if purposely checked on or off
        	
	        val = 2;

	        if (!$(featureIDs[count]).is(":indeterminate")) { // indeterminate; show both true results and false results
	        	if ($(featureIDs[count]).is(':checked')) // show true results
	        		val = 1;
	        	else // show false results
	        		val = 0;
	        }

	        features[key] = val;
	        count++;
        }

        var ajaxurl = 'search-ajax.php',
            data =  {	'action': clickBtnValue,
                        'address' : address,
                        'beds' : beds,
                        'rooms' : rooms,
            			'price' : price,
            			'rating' : rating,
                        'district' : district,
            			'features' : features
            		};

        $.post(ajaxurl, data, function (response) {

            $('.searchResults').html(response);
            //alert(response);
        });
    });

});