$(document).ready(function () {

    $('video').height( $(window).height());

    $('.thisOne,.thisTwo,.thisThree').height( $(window).height());

    $(document).on('click' , '.btnn',function(){
        // Scroll to a certain element
        document.querySelector('.thisTwo').scrollIntoView({
            behavior: 'smooth'
        });
    });


});

