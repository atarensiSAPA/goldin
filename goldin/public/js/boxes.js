// let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// console.log("Token: ", token); // Log the token

// $('#openBoxButton').click(function() {
//     console.log("Open Box Button clicked"); // Log when the button is clicked
//     $.ajax({
//         url: ajaxOpenBoxUrl, // Usar la variable global con la URL generada por Laravel
//         method: 'POST',
//         data: {
//             box_name: '{{ $boxes->first()->box_name }}',
//         },
//         headers: {
//             'X-CSRF-TOKEN': token
//         },
//         dataType: 'json',
//         success: function(data) {
//             console.log("Success data: ", data); // Log the data received on success
//             if (data.error) {
//                 console.log("Error: ", data.error); // Log the error if there is one
//                 alert(data.error);
//             } else {
//                 console.log("No error"); // Log when there is no error
//                 $('#coins').text(data.coins);
//                 let color = tinycolor(data.color).lighten(20).toString();
//                 $('#weapon-container').animate({
//                     backgroundColor: color,
//                     borderColor: data.color
//                 }, 600);
//                 let newImage = new Image();
//                 newImage.src = imagesPath + '/' + data.weapon.weapon_img;
//                 newImage.onload = function() {
//                     console.log("New image loaded"); // Log when the new image is loaded
//                     $('.weapon-image').addClass('fadeOut').one('animationend', function() {
//                         $(this).css('display', 'none');
//                         $('.weapon-image').attr('src', newImage.src).css({width: '400px', height: 'auto'});
//                         $('.weapon-image').css('display', '');
//                         $('.weapon-image').removeClass('fadeOut').addClass('fadeIn').one('animationend', function() {
//                             $(this).removeClass('fadeIn');
//                         });
//                     });
//                 }
//                 $('#weapon-message').html('<p>You have obtained a <span style="color:' + color + ';"> ' + ' ' +data.weapon.rarity.toUpperCase() + '</span> weapon</p>');
//             }
//         },
//     });
// });
// $(document).ready(function() {
//     $('#openBoxButtonDaily').click(function() {
//         $.ajax({
//             url: '/ajaxDailyOpenBox',
//             method: 'POST',
//             data: {
//                 box_name: '{{ $boxes->first()->box_name }}',
//                 _token: '{{ csrf_token() }}'
//             },
//             dataType: 'json',
//             success: function(data) {
//                 if (data.error) {
//                     alert(data.error);
//                 } else {
//                     console.log(data);
//                     $('#coins').text(data.coins);
//                     $('#coin-message').html('<p>You have obtained <span class="text-danger">' + data.coins + '</span> coins</p>');
//                 }
//             },
//         });
//     });
// });