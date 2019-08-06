function on(n) {
  document.getElementById(`project${n}-modal`).style.display = "block";
}

// Add New Project Cards in the funtion off() below
function off() {
  // Project card 1
  document.getElementById(`project1-modal`).style.display = "none";
  // Project card 2
  document.getElementById(`project2-modal`).style.display = "none";
  // Project card 3
  document.getElementById(`project3-modal`).style.display = "none";
  // Project card 4
  document.getElementById(`project4-modal`).style.display = "none";
}