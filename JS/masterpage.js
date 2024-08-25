const shrink_btn = document.querySelector(".botao-encolher");
const procurar = document.querySelector(".procurar");
const sidebar_acessos = document.querySelectorAll(".acesso-lateral a");
const active_tab = document.querySelector(".janela-ativa");
const shortcuts = document.querySelector(".acesso-lateral h4");
const tooltip_elements = document.querySelectorAll(".element-ferramenta");

let activeIndex;

shrink_btn.addEventListener("click", () => {
  document.body.classList.toggle("shrink");
  setTimeout(moveActiveTab, 400);

  shrink_btn.classList.add("hovered");

  setTimeout(() => {
    shrink_btn.classList.remove("hovered");
  }, 500);
});

procurar.addEventListener("click", () => {
  document.body.classList.remove("shrink");
  procurar.lastElementChild.focus();
});

function moveActiveTab() {
  let topPosition = activeIndex * 58 + 2.5;

  if (activeIndex > 4) {
    topPosition += shortcuts.clientHeight;
  }

  active_tab.style.top = `${topPosition}px`;
}

function changeacesso() {
  sidebar_acessos.forEach((sideacesso) => sideacesso.classList.remove("active"));
  this.classList.add("active");

  activeIndex = this.dataset.active;

  moveActiveTab();
}

sidebar_acessos.forEach((acesso) => acesso.addEventListener("click", changeacesso));

function showTooltip() {
  let tooltip = this.parentNode.lastElementChild;
  let spans = tooltip.children;
  let tooltipIndex = this.dataset.tooltip;

  Array.from(spans).forEach((sp) => sp.classList.remove("show"));
  spans[tooltipIndex].classList.add("show");

  tooltip.style.top = `${(100 / (spans.length * 2)) * (tooltipIndex * 2 + 1)}%`;
}

tooltip_elements.forEach((elem) => {
  elem.addEventListener("mouseover", showTooltip);
});

//Mudar página
function carregarPagina(url) {
  fetch(url)
      .then(response => {
          return response.text();
      })
      .then(html => {
          document.getElementById('content').innerHTML = html;
      })
      .catch(error => console.error('Erro ao carregar a página:', error));
}
