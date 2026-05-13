let includedTags = [];
const input = document.querySelector("#search");

function createTag(tagName) {
  const button = document.createElement("button");
  button.classList.add("tag");
  button.textContent = tagName;

  button.addEventListener("click", function() {
    button.remove();

    const cleanTag = tagName.trim().toLowerCase();
    const index = includedTags.indexOf(cleanTag);
    if(index !== -1) {
      includedTags.splice(index, 1);
    }

    hideArticles();
  });
  return button;
}

function hideArticles() {
  const articles = document.querySelectorAll(".article");
  if(includedTags.length === 0) {
    articles.forEach(article => article.classList.remove("hidden"));
    return;
  }

  let includedArticles = [];
  articles.forEach(article => {
    const tags = article.querySelectorAll(".tags p");
    tags.forEach(tagEl => {
      const tagText = tagEl.textContent.trim().toLowerCase();

      includedTags.forEach(searchTag => {
        if(tagText.includes(searchTag.toLowerCase())) {
          if(!includedArticles.includes(article)) {
            includedArticles.push(article);
          }
        }
      });
    });
  });

  articles.forEach(article => {
    if(includedArticles.includes(article)) {
      article.classList.remove("hidden");
    } else {
      article.classList.add("hidden");
    }
  });
}

function addSearchTerm(term) {
  if(term === undefined) {
    const term = input.value;
  }

  const cleanTerm = term.trim().toLowerCase();
  if(cleanTerm === "") return;

  if(!includedTags.includes(cleanTerm)) {
    includedTags.push(cleanTerm);
  }

  const tagButton = createTag(term);
  let tags = document.querySelector("#tags");
  tags.prepend(tagButton);
  hideArticles();

  input.value = "";
}

function initialize() {
  const params = new URLSearchParams(window.location.search);
  const tags = params.getAll("tag");
  const cleanedTags = tags.map(tag => tag.toLowerCase().trim());
  console.log(cleanedTags);

  cleanedTags.forEach(tag => {
    addSearchTerm(tag);
  });
}

input.addEventListener("keypress", function(event) {
  if(event.key === "Enter") {
    addSearchTerm(input.value);
    input.value = "";
  }
});
