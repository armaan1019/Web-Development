function saveScrollPosition() {
  const key = `scroll-pos-${document.title}`;
  localStorage.setItem(key, window.scrollY);
}

function restoreScrollPosition() {
  const key = `scroll-pos-${document.title}`;
  const savedPosition = localStorage.getItem(key);

  if(savedPosition !== null && !isNaN(savedPosition)) {
    window.scrollTo(0, Number(savedPosition));
  }
}

window.addEventListener("load", restoreScrollPosition);

let scrollTimeout;

window.addEventListener("scroll", function () {
  clearTimeout(scrollTimeout);

  scrollTimeout = setTimeout(function () {
    saveScrollPosition();
  }, 150);
});

const article = document.querySelector("article");
const downloadButton = document.querySelector("#download");

article.addEventListener("mouseup", function() {
  const selection = window.getSelection();

  if(selection.rangeCount === 0 || selection.toString().trim() === "") {
    return;
  }

  const range = selection.getRangeAt(0);

  const span = document.createElement("span");
  span.classList.add("highlight");

  try {
    range.surroundContents(span);

    selection.removeAllRanges();
  } catch (error) {
    console.log("Invalid selection");
  }
});

article.addEventListener("click", function(event) {
  if(event.target.classList.contains("highlight")) {
    const span = event.target;
    const textNode = document.createTextNode(span.textContent);

    span.parentNode.replaceChild(textNode, span);
  }
});

downloadButton.addEventListener("click", function() {
  const highlights = document.querySelectorAll(".highlight");
  const highlightArray = [];

  highlights.forEach(function (highlight) {
    highlightArray.push(highlight.textContent);
  });

  const jsonString = JSON.stringify(highlightArray);

  const encoded = encodeURIComponent(jsonString);

  const link = document.createElement("a");

  link.setAttribute("href", "data:text/json;charset=utf-8," + encoded);

  link.setAttribute("download", "highlights.json");

  link.click();
});
