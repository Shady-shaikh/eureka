(function () {
  const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

        let enddate =  (typeof $('#endeals_date').val() !== "undefined" || $('#endeals_date').val() !== null)?$('#endeals_date').val():new Date();
        let startdate =  (typeof $('#startdeals_date').val() !== "undefined" || $('#startdeals_date').val() !== null)?$('#startdeals_date').val():new Date();
  
        // let birthday = "Mar 26, 2021 00:00:00",
        today=new Date();
        // if(today >= new Date(startdate) && today <= new Date(enddate)){
        //     countDown = new Date(enddate).getTime();
        //  }else{
        //     countDown = new Date(startdate).getTime();
        //     document.getElementById("heading").innerHTML = "COMING SOON....";
            
        //  }
        countDown = new Date(enddate).getTime();
      if(today <= new Date(startdate)){
        document.getElementById("days").innerHTML = 0
        document.getElementById("hours").innerHTML = 0
        document.getElementById("minutes").innerHTML = 0
        document.getElementById("seconds").innerHTML = 0 
      }else{
      x = setInterval(function() {

        let now = new Date().getTime(),
            distance = countDown - now;
        if(typeof $('#days').val() !== "undefined" || $('#days').val() !== null)
        {
          document.getElementById("days").innerText = Math.floor(distance / (day)),
          document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
          document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
          document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);
          
        }
        //do something later when date is reached
        if (distance < 0) {
            document.getElementById("days").innerHTML = 0
            document.getElementById("hours").innerHTML = 0
            document.getElementById("minutes").innerHTML = 0
            document.getElementById("seconds").innerHTML = 0
            // document.getElementById("heading").innerHTML = "No Deals Available for Now";
          
          clearInterval(x);
        }
        //seconds
      }, 0)
    }
  }());
