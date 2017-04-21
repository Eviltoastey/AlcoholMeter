$( document ).ready(function() {

        if($('body').is('.drankPage')) {
            var drankid = $(".js-drank-data").attr("data-drankid");
            var pushedToDataBase = false;
            var route;
            var emptyWeight = 0;
            var filledWeight = 0;
            var interval = 1000;  // 1000 = 1 second, 3000 = 3 seconds
            var contains = 0;

            // if(emptyWeight == 0){

            //     $.ajax({
            //         type: 'GET',
            //         url: '/AlcoholMeter/data.json',
            //         dataType: 'json',
            //         success: function (data) {
            //             // $('#hidden').val(data);// first set the value
            //             console.log(parseFloat(data.weight));
            //
            //             if( parseFloat(data.weight) <= 50 && parseFloat(data.weight)  >= -50){
            //                 alert('zet een leeg glas op het viltje om het te wegen');
            //                 emptyWeight = parseFloat(data.weight);
            //             }
            //         }
            //     });
            // }
            //

            $('.getEmptyWeight').on('click', function () {

                alert('zet een leeg glas op het viltje om het te wegen');

                function getEmptyWeight() {
                    $.ajax({
                        type: 'GET',
                        url: '/AlcoholMeter/data.json',
                        dataType: 'json',
                        success: function (data) {

                            if (data.weight >= -1 && data.weight <= 1) {
                                alert('U heeft geen glas op het viltje gezet.');
                                emptyWeight = 0;
                            }
                            else {
                                emptyWeight = data.weight;
                                console.log('Weight set. glass weight is: ' + emptyWeight);

                                if(emptyWeight != 0 && filledWeight == 0) {
                                alert('zet een gevuld glas op het viltje');
                                }
                            }

                        },
                        // complete: function (data) {
                        //     // Schedule the next
                        //     setTimeout(getWeight, interval);
                        // }
                    });
                }

                setTimeout(getEmptyWeight, interval);
            });

                var weightList = [];

                function getWeight() {
                    $.ajax({
                        type: 'GET',
                        url: '/AlcoholMeter/data.json',
                        dataType: 'json',
                        success: function (data) {
                            if (emptyWeight != 0) {

                                contains = data.weight - emptyWeight;

                                for(var i = 0; i < 2; i++)
                                {
                                    weightList.push(contains);

                                    if(i == 3){
                                        contains = function( weightList ){
                                            return Math.max.apply( Math, weightList );
                                        };

                                        weightList = [];
                                    }
                                }


                                if(contains >= 1){
                                    $('#contains').text(contains);// first set the value
                                }

                                if(contains <= 1 && contains >= -1){
                                    $('#myModal').modal('show');
                                }
                                console.log(emptyWeight);
                                console.log(parseInt(data.weight));
                                console.log(contains);

                                if(pushedToDataBase == false){
                                    $.ajax({
                                        type: 'POST',
                                        url: "{{path('ajax_call')}}",
                                        contentType: 'application/json; charset=utf-8',
                                        data: JSON.stringify({id: drankid}),
                                        dataType: "json",
                                        success: function(response){
                                            alert(response);
                                            pushedToDataBase = true;
                                        }
                                    })

                                }
                            }

                        },
                        complete: function (data) {
                            // Schedule the next
                            setTimeout(getWeight, interval);
                        }
                    });
                }

                setTimeout(getWeight, interval);
            }


});
