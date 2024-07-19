const modal = document.querySelector('#modal');
const btn = document.querySelector('#modal-open');

btn.onclick = function () {
  modal.style.display = 'flex';
};

window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = 'none';
  }
};
