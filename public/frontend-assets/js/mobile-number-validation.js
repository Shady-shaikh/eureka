function mycontact(){
  var mob = document.getElementById("mobnumber").value;

  if(isNaN(mob))
  {
    document.getElementById("messages").innerHTML="* Please enter only number";
    return false;
  }

  if(mob.length<10)
  {
    document.getElementById("messages").innerHTML="* Please enter valid mobile number";
    return false;
  }

  if(mob.length>10)
  {
    document.getElementById("messages").innerHTML="* Please enter valid mobile number";
    return false;
  }

  if((mob.charAt(0)!=9) && (mob.charAt(0)!=7)  && (mob.charAt(0)!=8))
  {
    document.getElementById("messages").innerHTML="* Please enter valid mobile number";
    return false;
  }
}



function myRegistration(){
  var mob = document.getElementById("mobnumber").value;

  if(isNaN(mob))
  {
    document.getElementById("messages").innerHTML="* Please enter only number";
    return false;
  }

  if(mob.length<10)
  {
    document.getElementById("messages").innerHTML="* Please enter valid mobile number";
    return false;
  }

  if(mob.length>10)
  {
    document.getElementById("messages").innerHTML="* Please enter valid mobile number";
    return false;
  }

  if((mob.charAt(0)!=9) && (mob.charAt(0)!=7)  && (mob.charAt(0)!=8))
  {
    document.getElementById("messages").innerHTML="* Please enter valid mobile number";
    return false;
  }
}
