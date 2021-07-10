function selectFilter(type) {
  var allFilter = document.getElementById('allFilter');
  var videoFilter = document.getElementById('videoFilter');
  var imgFilter = document.getElementById('imgFilter');
  var toChange = [];

  switch (type) {
    case "all":
      toChange = [videoFilter, imgFilter];
      break;

    case "video":
      toChange = [allFilter, imgFilter];
      break;

    case "img":
      toChange = [allFilter, videoFilter];
      break;

    default:
      return;
  }

  var selected = document.getElementById("".concat(type, "Filter")).firstElementChild;
  selected.setAttribute('class', 'filterIcon-selected');
  toChange.forEach(function (button) {
    var svg = button.firstElementChild;
    svg.setAttribute('class', 'filterIcon');
  });
  displayRows(type);
}

function displayRows(type) {
  type = "".concat(type, "Row");
  var container = document.getElementsByClassName('container')[0].children;
  console.log(type);
  Array.prototype.forEach.call(container, function (element) {
    if (type !== 'allRow' && (element.classList.contains("videoRow") || element.classList.contains("imgRow"))) {
      if (element.classList.contains(type)) {
        element.classList.remove('hiddenRow');
      } else {
        element.classList.add('hiddenRow');
      }
    } else {
      element.classList.remove('hiddenRow');
    }
  });
}

$("#openBtn").click(function () {
  $("#navbar").toggle();
  $("#navbar").toggleClass("collapsed");
});


function displayAlert(type, message, time=2000){
  var alert = $("<div></div>").addClass("alert alert-"+type).append(message);
  $("#alert-container").append(alert);

  setTimeout(function (){
    alert.remove();
  }, time);

}
