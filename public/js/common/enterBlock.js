$(function(){
  $("input, select").on("keydown", function(evt){
    if (evt.key === "Enter") {
      return false;
    }
  });
});
